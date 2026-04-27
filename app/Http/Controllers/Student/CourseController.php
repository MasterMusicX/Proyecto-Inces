<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function catalog()
    {
        $courses = Course::where('status', 'published')
            ->with(['instructor', 'category'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(12);

        return view('student.courses.catalog', compact('courses'));
    }

    public function show($identifier)
    {
    
        $field = is_numeric($identifier) ? 'id' : 'slug';

        $course = Course::with(['instructor', 'modules.resources', 'category'])
            ->where($field, $identifier)
            ->firstOrFail();

        $isEnrolled = $course->students()->where('users.id', Auth::id())->exists();
        
        $enrollment = $isEnrolled
            ? Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->first()
            : null;

        return view('student.courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    public function enroll($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = Course::where($field, $identifier)->firstOrFail();
        
        $user = Auth::user();

        if ($course->students()->where('users.id', $user->id)->exists()) {
            return back()->with('info', 'Ya estás inscrito en este curso.');
        }

        if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
            return back()->with('error', 'Este curso ya alcanzó el cupo máximo de estudiantes.');
        }

        Enrollment::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'status'    => 'active',
        ]);

        return redirect()->route('student.courses.show', $course->slug ?? $course->id)
            ->with('success', '¡Te has inscrito exitosamente en ' . $course->title . '!');
    }

    // 👇 NUEVA FUNCIÓN PARA RETIRARSE DEL CURSO 👇
    public function withdraw($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = Course::where($field, $identifier)->firstOrFail();
        
        $user = Auth::user();

        // Buscamos si existe la inscripción
        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->first();

        if ($enrollment) {
            // Eliminamos la inscripción de la base de datos
            $enrollment->delete();
            return redirect()->route('student.dashboard')
                ->with('success', 'Te has retirado de ' . $course->title . ' exitosamente.');
        }

        return back()->with('error', 'No estás inscrito en este curso.');
    }
    // 👆 FIN DE LA MEJORA 👆

    public function learn($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        
        $course = Course::with(['modules.resources', 'instructor'])
            ->where($field, $identifier)
            ->firstOrFail();

        $isEnrolled = $course->students()->where('users.id', Auth::id())->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.courses.show', $course->slug ?? $course->id)
                ->with('error', 'Debes inscribirte primero para acceder al contenido.');
        }

        return view('student.courses.learn', compact('course'));
    }
}