<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Muestra la lista de entregables y justificativos subidos por el estudiante.
     */
    public function index()
    {
        $user = Auth::user();
        
        $submissions = StudentSubmission::where('user_id', $user->id)
            ->with(['course', 'module'])
            ->latest()
            ->paginate(15);

        // Cursos en los que el estudiante está inscrito con sus módulos
        $enrolledCourses = $user->enrolledCourses()->with('modules')->get();

        return view('student.submissions.index', compact('submissions', 'enrolledCourses'));
    }

    /**
     * Guarda una nueva tarea realizada o récipe/justificativo médico en formato PDF.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:assignment,medical_receipt,other',
            'course_id' => 'nullable|exists:courses,id',
            'module_id' => 'nullable|exists:modules,id',
            'notes'     => 'nullable|string|max:1000',
            'file'      => 'required|file|mimes:pdf|max:10240', // Obligatorio PDF, Máx 10 MB
        ], [
            'file.required' => 'Debes adjuntar un archivo en formato PDF.',
            'file.mimes'    => 'El archivo debe ser exclusivamente en formato PDF (.pdf).',
            'file.max'      => 'El archivo PDF no puede pesar más de 10 MB.',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        // Almacenar en storage/app/public/submissions
        $filePath = $file->store('submissions', 'public');

        StudentSubmission::create([
            'user_id'   => Auth::id(),
            'course_id' => $request->course_id,
            'module_id' => $request->module_id,
            'type'      => $request->type,
            'title'     => $request->title,
            'notes'     => $request->notes,
            'file_path' => $filePath,
            'file_name' => $originalName,
            'file_size' => $fileSize,
            'status'    => 'pending',
        ]);

        $tipoTexto = $request->type === 'medical_receipt' ? 'Récipe/Justificativo Médico' : 'Tarea/Entregable';

        return redirect()->route('student.submissions.index')
            ->with('success', "¡Tu {$tipoTexto} ha sido subido exitosamente en formato PDF!");
    }

    /**
     * Permite ver/descargar el archivo PDF subido.
     */
    public function file(StudentSubmission $submission)
    {
        // Verificar pertenencia o rol de profesor/admin
        $user = Auth::user();
        if ($submission->user_id !== $user->id && !$user->isInstructor() && !$user->isAdmin()) {
            abort(403, 'No tienes permiso para ver este documento.');
        }

        $fullPath = storage_path('app/public/' . $submission->file_path);

        if (!file_exists($fullPath)) {
            abort(404, 'El archivo PDF no se encuentra físicamente en el servidor.');
        }

        return response()->file($fullPath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $submission->file_name . '"',
        ]);
    }

    /**
     * Permite eliminar una entrega si aún está en estado pendiente.
     */
    public function destroy(StudentSubmission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Acceso denegado.');
        }

        if ($submission->status !== 'pending') {
            return back()->with('error', 'No puedes eliminar un entregable que ya fue revisado por el profesor.');
        }

        if (Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return back()->with('success', 'Documento PDF eliminado del sistema.');
    }
}
