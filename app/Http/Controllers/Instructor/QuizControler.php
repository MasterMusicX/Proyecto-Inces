<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Muestra el formulario de creación (Ya tienes el Blade).
     */
    public function create(Course $course)
    {
        return view('instructor.quizzes.create', compact('course'));
    }

    /**
     * Guarda la evaluación, sus preguntas y sus opciones.
     */
    public function store(Request $request, Course $course)
    {
        // 1. Validar los datos básicos
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_score' => 'required|numeric|min:0',
            'time_limit' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required|array|min:2',
        ]);

        try {
            DB::beginTransaction();

            // 2. Crear el Quiz
            $quiz = $course->quizzes()->create([
                'title' => $request->title,
                'description' => $request->description,
                'time_limit' => $request->time_limit,
                'passing_score' => $request->passing_score,
                'max_attempts' => $request->max_attempts ?? 1,
                'is_active' => false, // Se crea desactivado por defecto
            ]);

            // 3. Recorrer y guardar preguntas
            foreach ($request->questions as $index => $qData) {
                $question = $quiz->questions()->create([
                    'question_text' => $qData['text'],
                    'points' => $qData['points'],
                    'type' => 'multiple_choice', // Por ahora el estándar
                ]);

                // 4. Guardar las opciones de cada pregunta
                foreach ($qData['options'] as $oIndex => $oData) {
                    $question->options()->create([
                        'option_text' => $oData['text'],
                        // Comparamos si este índice es el que el profe marcó como correcto
                        'is_correct' => ($request->questions[$index]['correct_index'] == $oIndex),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('instructor.courses.show', $course->id)
                             ->with('success', '¡Evaluación creada exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el quiz: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Activar o desactivar el quiz (El switch del profe).
     */
    public function toggleStatus(Quiz $quiz)
    {
        $quiz->update(['is_active' => !$quiz->is_active]);
        
        $msg = $quiz->is_active ? 'Evaluación activada.' : 'Evaluación desactivada.';
        return back()->with('success', $msg);
    }

    /**
     * Eliminar evaluación.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Evaluación eliminada correctamente.');
    }
}