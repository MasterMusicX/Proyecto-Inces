<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // 🔥 IMPORTANTE: Agregamos Http para hablar con ImgBB
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('instructor_id', Auth::id())
            ->withCount(['enrollments', 'modules', 'resources'])
            ->latest()
            ->paginate(12);
        return view('instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        // Traemos las categorías para llenar el "select" del formulario
        $categories = Category::all();
        return view('instructor.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validamos los datos que el profe nos envió desde el formulario
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:course_categories,id',
            'status'      => 'required|in:published,draft',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Máximo 2MB
        ]);

        $data['instructor_id'] = Auth::id();
        
        // Creamos una URL amigable (slug) automáticamente basada en el título
        $data['slug'] = Str::slug($data['title'] . '-' . uniqid());

        // 🔥 NUEVA LÓGICA: Subir la foto a ImgBB 🔥
        if ($request->hasFile('thumbnail')) {
            try {
                // Convertimos la imagen a Base64 para enviarla
                $imageContent = base64_encode(file_get_contents($request->file('thumbnail')->path()));
                
                // Hacemos la petición POST a ImgBB
                $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                    'key'   => env('IMGBB_API_KEY'), // Asegúrate de tener esta variable en tu .env
                    'image' => $imageContent,
                ]);

                if ($response->successful()) {
                    // Si todo sale bien, guardamos el link directo en la base de datos
                    $data['thumbnail'] = $response->json('data.url');
                } else {
                    // Plan B: Si ImgBB rechaza la imagen, guardamos local
                    $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
                }
            } catch (\Exception $e) {
                // Plan C: Si no hay internet o explota la API, guardamos local
                $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            }
        }

        Course::create($data);

        return redirect()->route('instructor.courses.index')->with('success', '¡Curso creado exitosamente, chamo!');
    }
  
    public function show($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = \App\Models\Course::where($field, $identifier)->firstOrFail();

        Gate::authorize('view', $course);
        $course->load(['modules.resources', 'category']);
        
        $stats = [
            'students'  => $course->enrollments()->count(),
            'modules'   => $course->modules()->count(),
            'resources' => $course->resources()->count(),
            'completed' => $course->enrollments()->where('status', 'completed')->count(),
        ];
        
        $students = $course->students()->latest('enrollments.created_at')->limit(10)->get();
        
        return view('instructor.courses.show', compact('course', 'stats', 'students'));
    }

    public function students(Course $course)
    {
        Gate::authorize('view', $course);
        
        $students = $course->students()
            ->withPivot('id', 'status', 'progress_percentage', 'completed_at', 'created_at', 'final_grade', 'is_approved')
            ->orderByPivot('created_at', 'desc')
            ->paginate(20);
            
        return view('instructor.courses.students', compact('course', 'students'));
    }

    public function updateGrade(Request $request, $courseId, $studentId)
    {
        $request->validate([
            'final_grade' => 'required|numeric|min:0|max:20',
            'status'      => 'required|in:in_progress,approved,failed',
        ]);

        $course = Course::findOrFail($courseId);
        Gate::authorize('update', $course); 

        $course->students()->updateExistingPivot($studentId, [
            'final_grade' => $request->final_grade,
            'status'      => $request->status, 
        ]);

        return back()->with('success', '¡Calificación guardada y actualizada exitosamente!');
    }

    public function exportStudents(Course $course)
    {
        Gate::authorize('view', $course);
        
        $students = $course->students()->orderBy('name')->get();
        return view('instructor.courses.print-attendance', compact('course', 'students'));
    }

    public function modules(Course $course)
    {
        Gate::authorize('view', $course);
        $modules = $course->modules()->withCount('resources')->orderBy('sort_order')->get();
        return view('instructor.courses.modules', compact('course', 'modules'));
    }

    public function storeModule(Request $request, Course $course)
    {
        Gate::authorize('update', $course);
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'is_visible'   => 'boolean',
        ]);
        
        $data['course_id'] = $course->id;
        $data['sort_order'] = $course->modules()->max('sort_order') + 1;
        
        Module::create($data);
        return back()->with('success', 'Módulo creado.');
    }

    public function destroyModule(Course $course, Module $module)
    {
        Gate::authorize('update', $course);
        $module->delete();
        return back()->with('success', 'Módulo eliminado.');
    }
}