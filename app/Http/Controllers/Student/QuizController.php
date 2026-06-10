<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Muestra la evaluación al estudiante.
     */
    public function show(Course $course, Quiz $quiz)
    {
        // Verificar que el examen esté activo
        if (!$quiz->is_active) {
            return redirect()->back()->with('error', 'Esta evaluación se encuentra cerrada en este momento.');
        }

        // Cargamos las preguntas con sus opciones para la vista
        $quiz->load('questions.options');
        return view('student.quizzes.take', compact('course', 'quiz'));
    }

    /**
     * Procesa la entrega, sube foto a ImgBB, calcula nota (escala 20pts) y guarda todo.
     */
    public function submit(Request $request, Course $course, Quiz $quiz)
    {
        // 1. Validación estricta
        $request->validate([
            'answers'          => 'required|array',
            'proctoring_image' => 'required|string', // La foto es obligatoria por seguridad
        ]);

        try {
            DB::beginTransaction();

            $student = Auth::user();
            $correctAnswers = 0;
            $totalQuestions = $quiz->questions()->count();

            if ($totalQuestions === 0) {
                return back()->with('error', 'El examen no tiene preguntas configuradas.');
            }

            // 2. Lógica de corrección automática
            foreach ($quiz->questions as $question) {
                // Obtenemos el ID de la opción que marcó el estudiante
                $selectedOptionId = $request->answers[$question->id] ?? null;

                if ($selectedOptionId) {
                    // Verificamos en la BD si esa opción es la correcta
                    $isCorrect = $question->options()
                        ->where('id', $selectedOptionId)
                        ->where('is_correct', true)
                        ->exists();

                    if ($isCorrect) {
                        $correctAnswers++;
                    }
                }
            }

            // 3. Procesar la foto y subirla a ImgBB
            $imageUrl = null;
            if ($request->proctoring_image) {
                // Limpiamos el string Base64 (ImgBB no quiere el encabezado "data:image/jpeg;base64,")
                $imgBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $request->proctoring_image);
                
                // Hacemos la petición a la API de ImgBB
                $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                    'key'   => config('services.imgbb.key'),
                    'image' => $imgBase64,
                ]);

                if ($response->successful()) {
                    $imageUrl = $response->json('data.url');
                } else {
                    // Si falla ImgBB, cancelamos todo para que el estudiante intente de nuevo y no pierda su nota
                    throw new \Exception('Hubo un problema procesando la imagen de seguridad con la nube.');
                }
            }

            // 4. Calcular la nota final en escala de 20 puntos del INCES
            $finalGrade = ($correctAnswers / $totalQuestions) * 20;
            $finalGrade = round($finalGrade, 1);
            $status = $finalGrade >= 10 ? 'approved' : 'failed'; // Aprobado con 10 o más

            // 5. Crear el registro detallado del intento (Para la auditoría del profesor)
            QuizAttempt::create([
                'quiz_id'             => $quiz->id,
                'student_id'          => $student->id,
                'score'               => $finalGrade, // Guardamos la nota sobre 20
                'started_at'          => Carbon::now()->subMinutes($quiz->time_limit ?? 30),
                'completed_at'        => Carbon::now(),
                'proctoring_image'    => $imageUrl, // Enlace directo a ImgBB
                'suspicious_behavior' => $request->suspicious_behavior == '1',
            ]);

            // 6. 🔥 LA MAGIA 🔥 Actualizar la nota final oficial en el curso para que la vea el profesor
            $course->students()->updateExistingPivot($student->id, [
                'final_grade'         => $finalGrade,
                'status'              => $status,
                'progress_percentage' => 100, // Terminó el curso
                'completed_at'        => now(),
            ]);

            DB::commit();

            return redirect()->route('student.dashboard')->with('success', "¡Evaluación finalizada con éxito! Tu nota definitiva es: {$finalGrade}/20");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error técnico: ' . $e->getMessage());
        }
    }
}