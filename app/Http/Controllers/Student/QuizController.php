<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Muestra la evaluación al estudiante.
     */
    public function show(Quiz $quiz)
    {
        // Cargamos las preguntas con sus opciones para la vista
        $quiz->load('questions.options');
        return view('student.quizzes.show', compact('quiz'));
    }

    /**
     * Procesa la entrega, calcula nota y guarda proctoring.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        // 1. Validación estricta
        $request->validate([
            'answers' => 'required|array',
            'proctoring_image' => 'required', // La foto es obligatoria por seguridad
        ]);

        try {
            DB::beginTransaction();

            $earnedPoints = 0;

            // 2. Lógica de corrección automática
            foreach ($quiz->questions as $question) {
                // Obtenemos el ID de la opción que marcó el chamo para esta pregunta
                $selectedOptionId = $request->answers[$question->id] ?? null;

                if ($selectedOptionId) {
                    // Verificamos en la BD si esa opción es la correcta
                    $isCorrect = $question->options()
                        ->where('id', $selectedOptionId)
                        ->where('is_correct', true)
                        ->exists();

                    if ($isCorrect) {
                        $earnedPoints += $question->points;
                    }
                }
            }

            // 3. Procesar la foto (De Base64 a archivo físico)
            $imagePath = null;
            if ($request->proctoring_image) {
                $img = $request->proctoring_image;
                $img = str_replace('data:image/jpeg;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                
                // Carpeta: storage/app/public/proctoring/
                $fileName = 'proctoring/user_' . Auth::user()->id. '_quiz_' . $quiz->id . '_' . time() . '.jpg';
                Storage::disk('public')->put($fileName, $data);
                $imagePath = $fileName;
            }

            // 4. Crear el registro del intento
            QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => Auth::user()->id,
                'score' => $earnedPoints,
                'started_at' => Carbon::now()->subMinutes($quiz->time_limit), // Tiempo estimado de inicio
                'completed_at' => Carbon::now(),
                'proctoring_image' => $imagePath,
                'suspicious_behavior' => $request->suspicious_behavior == '1',
            ]);

            DB::commit();

            return redirect()->route('student.courses.show', $quiz->course_id)
                             ->with('success', "¡Evaluación entregada! Tu puntuación fue de $earnedPoints puntos.");

        } catch (\Exception $e) {
            DB::rollBack();
            // Esto te ayuda a debuguear si algo sale mal
            return back()->with('error', 'Error técnico: ' . $e->getMessage());
        }
    }
}