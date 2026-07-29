<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class QuizController extends Controller
{
    /**
     * Muestra el formulario de creación de evaluación.
     */
    public function create(Course $course)
    {
        // Verifica si el instructor es dueño de este curso
        Gate::authorize('update', $course); 
        
        return view('instructor.quizzes.create', compact('course'));
    }

    /**
     * Guarda la evaluación, sus preguntas y sus opciones en la base de datos.
     */
    public function store(Request $request, Course $course)
    {
        // Verifica si el instructor es dueño de este curso
        Gate::authorize('update', $course);

        // 1. Validaciones estrictas del formulario (Frontend y Backend)
        $request->validate([
            'title'                     => 'required|string|max:255',
            'passing_score'             => 'required|numeric|min:1|max:20',
            'time_limit'                => 'required|integer|min:1',
            'max_attempts'              => 'nullable|integer|min:1',
            'questions'                 => 'required|array|min:1',
            'questions.*.text'          => 'required|string',
            'questions.*.points'        => 'required|integer|min:1',
            'questions.*.options'       => 'required|array|min:2',
            // 🔥 MEJORA: Obligamos a que el profe marque obligatoriamente un radio button como respuesta correcta
            'questions.*.correct_index' => 'required|integer', 
        ], [
            // Mensaje personalizado para cuando el profe olvida marcar la correcta
            'questions.*.correct_index.required' => 'Debes marcar el círculo de la respuesta correcta en todas las preguntas.',
        ]);

        try {
            // Iniciamos una transacción: Si algo falla, Laravel echa todo para atrás y no ensucia la base de datos
            DB::beginTransaction();

            // 🔥 CORRECCIÓN: Si ya existía un examen viejo en este curso, lo borramos para no acumular basura (Relación 1 a 1)
            if ($course->quiz) {
                $course->quiz->delete();
            }

            // Usamos quiz() en SINGULAR porque un curso "tiene un" (hasOne) examen
            $quiz = $course->quiz()->create([
                'title'         => $request->title,
                'time_limit'    => $request->time_limit,
                'passing_score' => $request->passing_score,
                'max_attempts'  => $request->max_attempts ?? 1,
                'is_active'     => false, // Por seguridad, nace apagado (modo borrador)
            ]);

            // Recorrer el array de preguntas que mandó Alpine.js
            foreach ($request->questions as $index => $qData) {
                // Creamos la pregunta
                $question = $quiz->questions()->create([
                    'question_text' => $qData['text'],
                    'points'        => $qData['points'],
                    'type'          => 'multiple_choice',
                ]);

                // Recorrer y guardar las opciones de esa pregunta específica
                foreach ($qData['options'] as $oIndex => $oData) {
                    $question->options()->create([
                        'option_text' => $oData['text'],
                        // Si el índice de esta opción coincide con el que marcó el profe (correct_index), la guardamos como True
                        'is_correct'  => ($qData['correct_index'] == $oIndex),
                    ]);
                }
            }

            // Todo salió bien, hacemos el Commit a la Base de Datos
            DB::commit();
            
            // Redirigimos al instructor a la vista de su curso con un mensaje de éxito
            return redirect()->route('instructor.courses.show', $course)
                             ->with('success', '¡Evaluación creada y guardada exitosamente en el sistema!');

        } catch (\Exception $e) {
            // Si algo explota (Error 500, base de datos caída), Laravel hace Rollback
            DB::rollBack();
            return back()->with('error', 'Error técnico al guardar la evaluación: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Activa o desactiva la visibilidad del examen para los estudiantes.
     */
    public function toggleStatus(Course $course, Quiz $quiz)
    {
        Gate::authorize('update', $course);

        // Invertimos el valor booleano actual
        $quiz->update(['is_active' => !$quiz->is_active]);
        
        $msg = $quiz->is_active ? 'Evaluación activada y visible para los estudiantes.' : 'Evaluación oculta/desactivada.';
        return back()->with('success', $msg);
    }

    /**
     * Elimina permanentemente la evaluación y todo su contenido.
     */
    public function destroy(Course $course, Quiz $quiz)
    {
        Gate::authorize('update', $course);

        $quiz->delete();
        return back()->with('success', 'Evaluación eliminada del sistema.');
    }
}
