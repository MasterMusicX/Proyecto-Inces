<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function catalog()
    {
        $courses = Course::where('status', 'published')
            ->with(['instructor', 'category'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(13);

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

        // Validación 2: ¿Ya está inscrito?
        if ($course->students()->where('users.id', $user->id)->exists()) {
            return back()->with('info', 'Ya estás inscrito en este curso.');
        }

        // Validación 3: ¿Hay cupos disponibles?
        if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
            return back()->with('error', 'Este curso ya alcanzó el cupo máximo de estudiantes.');
        }

        // 🔥 MEJORA: Validación 4 - Sistema de Prelaciones (Prerrequisitos) 🔥
        if ($course->prerequisite_id) {
            // Buscamos si el estudiante tiene ese curso previo COMPLETADO
            $hasCompletedPre = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->prerequisite_id)
                ->where('status', 'completed') // ¡Tiene que estar aprobado!
                ->exists();

            // Si no lo tiene, lo rebotamos con un mensaje elegante
            if (!$hasCompletedPre) {
                $cursoRequerido = Course::find($course->prerequisite_id)->title;
                return back()->with('error', "No puedes inscribir este curso aún. Debes aprobar primero: {$cursoRequerido}.");
            }
        }

        // Si pasa todas las validaciones, lo inscribimos
        Enrollment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'status'              => 'active',
            'progress_percentage' => 1 // Inicia con 1% de progreso
        ]);

        return redirect()->route('student.courses.show', $course->slug ?? $course->id)
            ->with('success', '¡Te has inscrito exitosamente en ' . $course->title . '!');
    }

    // 👇 FUNCIÓN PARA RETIRARSE DEL CURSO 👇
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
    // 👆 FIN DEL RETIRO 👆

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

    // 🔥 NUEVA MEJORA: Actualizar progreso en tiempo real 🔥
    public function updateProgress(Request $request, $identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = Course::where($field, $identifier)->firstOrFail();
        $user = Auth::user();

        // 1. Buscamos la inscripción activa del estudiante
        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->firstOrFail();

        // 2. Matemática del progreso
        $totalModules = $course->modules()->count();
        
        if ($totalModules == 0) {
            return back()->with('error', 'El curso aún no tiene contenido para avanzar.');
        }

        $percentagePerModule = 100 / $totalModules;
        $newProgress = $enrollment->progress_percentage + $percentagePerModule;

        // Limitar a 100% máximo
        if ($newProgress >= 100) {
            $newProgress = 100;
            $status = 'completed'; // Se marca como culminado
            $completedAt = now();
        } else {
            $status = 'active';
            $completedAt = $enrollment->completed_at; // Mantiene el valor actual (null)
        }

        // 3. Guardar en base de datos
        $enrollment->update([
            'progress_percentage' => $newProgress,
            'status'              => $status,
            'completed_at'        => $completedAt
        ]);

        if ($newProgress == 100) {
            return back()->with('success', '¡Felicidades! Has completado el curso al 100%.');
        }

        return back()->with('success', 'Progreso guardado. Llevas un ' . number_format($newProgress, 0) . '% completado.');
    }
}