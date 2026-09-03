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
        
        // Cursos pertenecientes al instructor con sus módulos
        $courses = Course::where('instructor_id', $user->id)
            ->with('modules')
            ->get();
        $courseIds = $courses->pluck('id')->toArray();

        $query = StudentSubmission::whereIn('course_id', $courseIds)
            ->with(['user', 'course', 'module'])
            ->latest();

        // Filtro opcional por curso
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filtro opcional por módulo
        if ($request->filled('module_id')) {
            $query->where('module_id', $request->module_id);
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
     * Revisa (Aprobar o Rechazar) una tarea o récipe médico con nota y Matriz de Habilidades INCES.
     */
    public function review(Request $request, StudentSubmission $submission)
    {
        $request->validate([
            'status'     => 'required|in:approved,rejected',
            'grade'      => 'nullable|numeric|min:0|max:20',
            'max_grade'  => 'nullable|numeric|min:1|max:100',
            'feedback'   => 'nullable|string|max:1000',
            'rubric'     => 'nullable|array',
            'rubric.technical_skill' => 'nullable|integer|min:1|max:5',
            'rubric.work_quality'    => 'nullable|integer|min:1|max:5',
            'rubric.safety_standards' => 'nullable|integer|min:1|max:5',
            'rubric.innovation'      => 'nullable|integer|min:1|max:5',
            'rubric.badge'           => 'nullable|string|max:255',
        ]);

        // Verificar que el curso pertenece al instructor
        if ($submission->course_id && $submission->course->instructor_id !== Auth::id()) {
            abort(403, 'No tienes permiso para revisar entregables de este curso.');
        }

        $rubricData = $request->input('rubric', []);
        
        $submission->update([
            'status'       => $request->status,
            'grade'        => $request->filled('grade') ? $request->grade : null,
            'max_grade'    => $request->filled('max_grade') ? $request->max_grade : 20,
            'skill_rubric' => !empty($rubricData) ? $rubricData : $submission->skill_rubric,
            'feedback'     => $request->feedback,
            'reviewed_at'  => now(),
        ]);

        $estadoTexto = $request->status === 'approved' ? 'aprobado' : 'rechazado';
        $notaTexto = $request->filled('grade') ? " con nota de {$request->grade}/20 pts" : '';

        return back()->with('success', "El entregable de {$submission->user->name} ha sido {$estadoTexto}{$notaTexto} exitosamente.");
    }
}
