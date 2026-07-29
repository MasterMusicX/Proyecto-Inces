<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\StudentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Muestra la lista de entregables y récipes médicos enviados por los estudiantes de los cursos del instructor.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Cursos pertenecientes al instructor
        $courses = $user->coursesAsInstructor;
        $courseIds = $courses->pluck('id')->toArray();

        $query = StudentSubmission::whereIn('course_id', $courseIds)
            ->with(['user', 'course'])
            ->latest();

        // Filtro opcional por curso
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filtro opcional por tipo (assignment / medical_receipt)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro opcional por estado (pending / approved / rejected)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->paginate(20);

        return view('instructor.submissions.index', compact('submissions', 'courses'));
    }

    /**
     * Revisa (Aprobar o Rechazar) una tarea o récipe médico con comentarios.
     */
    public function review(Request $request, StudentSubmission $submission)
    {
        $request->validate([
            'status'   => 'required|in:approved,rejected',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Verificar que el curso pertenece al instructor
        if ($submission->course_id && $submission->course->instructor_id !== Auth::id()) {
            abort(403, 'No tienes permiso para revisar entregables de este curso.');
        }

        $submission->update([
            'status'      => $request->status,
            'feedback'    => $request->feedback,
            'reviewed_at' => now(),
        ]);

        $estadoTexto = $request->status === 'approved' ? 'aprobado' : 'rechazado';

        return back()->with('success', "El entregable de {$submission->user->name} ha sido {$estadoTexto} exitosamente.");
    }
}
