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
use Illuminate\Support\Str;

// Si estás usando la notificación, asegúrate de importarla aquí arriba
// use App\Notifications\GradeAssignedNotification;

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
        // Validamos que no metan datos vacíos o locos
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:published,draft',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Máximo 2MB
        ]);

        $data['instructor_id'] = Auth::id();
        
        // Creamos una URL amigable (slug) automáticamente basada en el título
        $data['slug'] = Str::slug($data['title'] . '-' . uniqid());

        // Si el profe subió una foto, la guardamos en la carpeta 'courses/thumbnails'
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        Course::create($data);

        return redirect()->route('instructor.courses.index')->with('success', '¡Curso creado exitosamente, chamo!');
    }
  
    public function show($identifier)
    {
        // 1. Averiguar si buscar por ID o por Slug
        $field = is_numeric($identifier) ? 'id' : 'slug';

        // 2. Buscar el curso en la base de datos PRIMERO
        $course = \App\Models\Course::where($field, $identifier)->firstOrFail();

        // 3. AHORA SÍ, pasamos por el Gate de seguridad (El vigilante)
        Gate::authorize('view', $course);

        // 4. Cargamos las relaciones que necesitas para tu vista
        $course->load(['modules.resources', 'category']);
        
        // 5. Calculamos tus estadísticas
        $stats = [
            'students'  => $course->enrollments()->count(),
            'modules'   => $course->modules()->count(),
            'resources' => $course->resources()->count(),
            'completed' => $course->enrollments()->where('status', 'completed')->count(),
        ];
        
        // 6. Traemos los estudiantes
        $students = $course->students()->latest('enrollments.created_at')->limit(10)->get();
        
        // 7. Retornamos la vista
        return view('instructor.courses.show', compact('course', 'stats', 'students'));
    }

    public function students(Course $course)
    {
        Gate::authorize('view', $course);
        
        // Aquí traemos a los estudiantes y las columnas adicionales de la tabla pivote
        $students = $course->students()
            ->withPivot('id', 'status', 'progress_percentage', 'completed_at', 'created_at', 'final_grade', 'is_approved')
            ->orderByPivot('created_at', 'desc')
            ->paginate(20);
            
        return view('instructor.courses.students', compact('course', 'students'));
    }

    // 👇 AQUÍ ESTÁ LA NUEVA FUNCIÓN PARA GUARDAR LA NOTA 👇
    public function updateGrade(Request $request, $courseId, $studentId)
    {
        // Validamos que el instructor no meta números locos
        $request->validate([
            'final_grade' => 'required|numeric|min:0|max:20',
            'is_approved' => 'required|boolean'
        ]);

        $course = Course::findOrFail($courseId);
        
        // Verificamos que este instructor sea el dueño del curso
        Gate::authorize('update', $course); 

        // Actualizamos las columnas en la tabla pivote de ese estudiante específico
        $course->students()->updateExistingPivot($studentId, [
            'final_grade' => $request->final_grade,
            'is_approved' => $request->is_approved,
        ]);

        /* // Si activaste las notificaciones, puedes descomentar esto:
        $student = \App\Models\User::find($studentId);
        $student->notify(new GradeAssignedNotification($course, $request->final_grade, $request->is_approved));
        */

        return back()->with('success', '¡Nota guardada y actualizada exitosamente!');
    }

    public function modules(Course $course)
    {
        Gate::authorize('view', $course);
        // 🛠️ CORRECCIÓN: Cambiado 'order' por 'sort_order'
        $modules = $course->modules()->withCount('resources')->orderBy('sort_order')->get();
        return view('instructor.courses.modules', compact('course', 'modules'));
    }

    public function storeModule(Request $request, Course $course)
    {
        Gate::authorize('update', $course);
        // 🛠️ CORRECCIÓN: Cambiado 'is_published' por 'is_visible' para que coincida con tu BD
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'is_visible'   => 'boolean',
        ]);
        
        $data['course_id'] = $course->id;
        // 🛠️ CORRECCIÓN: Cambiado 'order' por 'sort_order'
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