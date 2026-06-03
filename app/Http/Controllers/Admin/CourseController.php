<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // 🔥 IMPORTANTE: Importamos el cliente HTTP

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
        return view('admin.courses.create', compact('instructors', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'objectives'     => 'nullable|string',
            'instructor_id'  => 'required|exists:users,id',
            'category_id'    => 'nullable|exists:course_categories,id',
            'level'          => 'required|in:basico,intermedio,avanzado',
            'duration_hours' => 'nullable|integer|min:0',
            'max_students'   => 'nullable|integer|min:1',
            'status'         => 'required|in:draft,published,archived',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        $data['is_featured'] = $request->has('is_featured');

        // 🔥 PLAN B: SUBIDA A IMGBB PARA CREAR (STORE) 🔥
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
        return view('admin.courses.edit', compact('course', 'instructors', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'objectives'     => 'nullable|string',
            'instructor_id'  => 'required|exists:users,id',
            'category_id'    => 'nullable|exists:course_categories,id',
            'level'          => 'required|in:basico,intermedio,avanzado',
            'duration_hours' => 'nullable|integer|min:0',
            'max_students'   => 'nullable|integer|min:1',
            'status'         => 'required|in:draft,published,archived',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        $data['is_featured'] = $request->has('is_featured');

        // 🔥 PLAN B: SUBIDA A IMGBB PARA ACTUALIZAR (UPDATE) 🔥
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
        // Nota: Como la imagen está en ImgBB (URL externa), ya no necesitamos borrarla del Storage local.
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado.');
    }

    // ========================================================================
    // 🔥 MÓDULO DE INSCRIPCIÓN FORZADA (SUPERPODER DEL ADMIN) 🔥
    // ========================================================================

    public function showForceEnroll()
    {
        $courses = Course::orderBy('title', 'asc')->get();
        return view('admin.courses.force-enroll', compact('courses'));
    }

    public function forceEnroll(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:users,email',
            'course_id' => 'required|exists:courses,id',
        ], [
            'email.exists' => 'El correo electrónico ingresado no coincide con ningún estudiante registrado.',
        ]);

        $student = User::where('email', $request->email)->first();

        if ($student->role !== 'student') {
            return back()->withInput()->with('error', 'El usuario seleccionado no tiene un rol de estudiante.');
        }

        $alreadyEnrolled = $student->enrollments()->where('course_id', $request->course_id)->exists();
        if ($alreadyEnrolled) {
            return back()->withInput()->with('error', 'El estudiante ya se encuentra inscrito en esta formación.');
        }

        $student->enrollments()->create([
            'course_id' => $request->course_id,
            'status'    => 'active',
            'progress_percentage' => 0.00
        ]);

        $courseTitle = Course::find($request->course_id)->title;

        return redirect()->route('admin.courses.force-enroll')
            ->with('success', "¡Acción ejecutada! El estudiante {$student->name} ha sido inscrito forzosamente en: \"{$courseTitle}\".");
    }
}