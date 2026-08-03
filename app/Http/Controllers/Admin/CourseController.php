<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\StudentModuleApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['instructor', 'category'])
            ->withCount('enrollments')
            ->when($request->search, fn($q) =>
                $q->where('title', 'ilike', '%' . $request->search . '%')
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        $categories  = Category::orderBy('name')->get();
        $courses     = Course::orderBy('title')->get();
        return view('admin.courses.create', compact('instructors', 'categories', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'objectives'      => 'nullable|string',
            'instructor_id'   => 'required|exists:users,id',
            'category_id'     => 'nullable|exists:course_categories,id',
            'prerequisite_id' => 'nullable|exists:courses,id',
            'level'           => 'required|in:basico,intermedio,avanzado',
            'duration_hours'  => 'nullable|integer|min:0',
            'max_students'    => 'nullable|integer|min:1',
            'status'          => 'required|in:draft,published,archived',
            'thumbnail'       => 'nullable|image|max:2048',
        ]);

        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('thumbnail')) {
            $imagePath = $request->file('thumbnail')->getRealPath();
            $imageBase64 = base64_encode(file_get_contents($imagePath));

            $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => $imageBase64,
            ]);

            if ($response->successful()) {
                $data['thumbnail'] = $response->json('data.url');
            } else {
                return back()->withInput()->with('error', 'Hubo un problema de conexión al subir la imagen. Intenta de nuevo.');
            }
        }

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        
        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', 'Curso creado exitosamente.');
    }

    public function edit(Course $course)
    {
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        $categories  = Category::orderBy('name')->get();
        $courses     = Course::where('id', '!=', $course->id)->orderBy('title')->get();
        return view('admin.courses.edit', compact('course', 'instructors', 'categories', 'courses'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'objectives'      => 'nullable|string',
            'instructor_id'   => 'required|exists:users,id',
            'category_id'     => 'nullable|exists:course_categories,id',
            'prerequisite_id' => 'nullable|exists:courses,id',
            'level'           => 'required|in:basico,intermedio,avanzado',
            'duration_hours'  => 'nullable|integer|min:0',
            'max_students'    => 'nullable|integer|min:1',
            'status'          => 'required|in:draft,published,archived',
            'thumbnail'       => 'nullable|image|max:2048',
        ]);

        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('thumbnail')) {
            $imagePath = $request->file('thumbnail')->getRealPath();
            $imageBase64 = base64_encode(file_get_contents($imagePath));

            $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => $imageBase64,
            ]);

            if ($response->successful()) {
                $data['thumbnail'] = $response->json('data.url');
            } else {
                return back()->withInput()->with('error', 'Hubo un problema de conexión al actualizar la imagen. Intenta de nuevo.');
            }
        }

        $course->update($data);
        
        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado exitosamente.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado.');
    }

    // ========================================================================
    // MÓDULO DE INSCRIPCIÓN POR PRELACIÓN Y MÓDULO (SUPERPODER DEL ADMIN)
    // ========================================================================

    public function showForceEnroll()
    {
        $courses  = Course::with(['modules', 'prerequisite'])->orderBy('title', 'asc')->get();
        $students = User::where('role', 'student')->orderBy('name', 'asc')->get();
        return view('admin.courses.force-enroll', compact('courses', 'students'));
    }

    public function forceEnroll(Request $request)
    {
        $request->validate([
            'email'           => 'required|email|exists:users,email',
            'course_id'       => 'required|exists:courses,id',
            'enrollment_type' => 'required|in:full,module',
            'module_id'       => 'required_if:enrollment_type,module|nullable|exists:modules,id',
        ], [
            'email.exists'             => 'El correo electrónico ingresado no coincide con ningún estudiante registrado.',
            'module_id.required_if'    => 'Debes seleccionar el módulo específico para realizar la inscripción por módulo.',
        ]);

        $student = User::where('email', $request->email)->first();

        if ($student->role !== 'student') {
            return back()->withInput()->with('error', 'El usuario seleccionado no tiene un rol de estudiante.');
        }

        $course = Course::with('modules')->findOrFail($request->course_id);

        $alreadyEnrolled = $student->enrollments()
            ->where('course_id', $course->id)
            ->when($request->enrollment_type === 'module', function($q) use ($request) {
                $q->where('module_id', $request->module_id);
            }, function($q) {
                $q->whereNull('module_id');
            })
            ->exists();

        if ($alreadyEnrolled) {
            return back()->withInput()->with('error', 'El estudiante ya se encuentra inscrito en esta modalidad para esta formación.');
        }

        // Crear registro de inscripción expres/prelado
        Enrollment::create([
            'user_id'             => $student->id,
            'course_id'           => $course->id,
            'module_id'           => $request->enrollment_type === 'module' ? $request->module_id : null,
            'enrollment_type'     => $request->enrollment_type,
            'status'              => 'active',
            'progress_percentage' => $request->enrollment_type === 'module' ? 10.00 : 1.00
        ]);

        // Si la inscripción es a un módulo específico, habilitar/aprobar el módulo o módulos previos si corresponde
        if ($request->enrollment_type === 'module' && $request->module_id) {
            $selectedModule = Module::find($request->module_id);
            if ($selectedModule) {
                // Registrar aprobación o habilitación activa de módulo
                StudentModuleApproval::firstOrCreate([
                    'user_id'   => $student->id,
                    'module_id' => $selectedModule->id,
                ], [
                    'is_approved' => false
                ]);
            }
        }

        $typeLabel = $request->enrollment_type === 'module' ? 'módulo específico' : 'curso completo por prelación';

        return redirect()->route('admin.courses.force-enroll')
            ->with('success', "¡Inscripción exitosa! El estudiante {$student->name} {$student->last_name} fue registrado al {$typeLabel} en: \"{$course->title}\".");
    }
}