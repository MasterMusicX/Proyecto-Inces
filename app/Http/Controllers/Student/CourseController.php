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
            ->with(['instructor', 'category', 'prerequisite'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(13);

        return view('student.courses.catalog', compact('courses'));
    }

    public function show($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';

        $course = Course::with(['instructor', 'modules.resources', 'category', 'quiz', 'prerequisite'])
            ->where($field, $identifier)
            ->firstOrFail();

        $isEnrolled = $course->students()->where('users.id', Auth::id())->exists();
        
        $enrollment = $isEnrolled
            ? Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->first()
            : null;

        return view('student.courses.show', compact('course', 'isEnrolled', 'enrollment'));
    }

    public function enroll(Request $request, $identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = Course::where($field, $identifier)->firstOrFail();
        
        $user = Auth::user();
        $enrollmentType = $request->input('enrollment_type', 'full');
        $moduleId       = $request->input('module_id');

        // Validación: ¿Ya está inscrito?
        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->when($enrollmentType === 'module' && $moduleId, function($q) use ($moduleId) {
                $q->where('module_id', $moduleId);
            })
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('info', 'Ya estás inscrito en esta formación.');
        }

        // Validación: ¿Hay cupos disponibles?
        if ($course->max_students && $course->enrollments()->count() >= $course->max_students) {
            return back()->with('error', 'Este curso ya alcanzó el cupo máximo de estudiantes.');
        }

        // Validación - Sistema de Prelaciones (Prerrequisitos)
        if ($course->prerequisite_id) {
            $hasCompletedPre = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->prerequisite_id)
                ->where('status', 'completed')
                ->exists();

            if (!$hasCompletedPre) {
                $cursoRequerido = Course::find($course->prerequisite_id)->title;
                return back()->with('error', "No puedes inscribir este curso aún. Debes aprobar primero: {$cursoRequerido}.");
            }
        }

        // Inscribir al estudiante
        Enrollment::create([
            'user_id'             => $user->id,
            'course_id'           => $course->id,
            'module_id'           => $enrollmentType === 'module' ? $moduleId : null,
            'enrollment_type'     => $enrollmentType,
            'status'              => 'active',
            'progress_percentage' => 1
        ]);

        $msg = $enrollmentType === 'module' 
            ? '¡Te has inscrito exitosamente al módulo específico de ' . $course->title . '!' 
            : '¡Te has inscrito exitosamente en ' . $course->title . '!';

        return redirect()->route('student.courses.show', $course->slug ?? $course->id)
            ->with('success', $msg);
    }

    public function withdraw($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = Course::where($field, $identifier)->firstOrFail();
        
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->route('student.dashboard')
                ->with('success', 'Te has retirado de ' . $course->title . ' exitosamente.');
        }

        return back()->with('error', 'No estás inscrito en este curso.');
    }

    public function learn($identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        
        $course = Course::with(['modules.resources', 'instructor', 'quiz'])
            ->where($field, $identifier)
            ->firstOrFail();

        $isEnrolled = $course->students()->where('users.id', Auth::id())->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.courses.show', $course->slug ?? $course->id)
                ->with('error', 'Debes inscribirte primero para acceder al contenido.');
        }

        return view('student.courses.learn', compact('course'));
    }

    public function updateProgress(Request $request, $identifier)
    {
        $field = is_numeric($identifier) ? 'id' : 'slug';
        $course = Course::where($field, $identifier)->firstOrFail();
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->firstOrFail();

        $totalModules = $course->modules()->count();
        
        if ($totalModules == 0) {
            return back()->with('error', 'El curso aún no tiene contenido para avanzar.');
        }

        $percentagePerModule = 100 / $totalModules;
        $newProgress = $enrollment->progress_percentage + $percentagePerModule;

        if ($newProgress >= 100) {
            $newProgress = 100;
            $status = 'completed';
            $completedAt = now();
        } else {
            $status = 'active';
            $completedAt = $enrollment->completed_at;
        }

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