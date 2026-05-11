<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Question (Pregunta)
 * * Define la estructura de las interrogantes dentro de una evaluación.
 * Soporta diferentes tipos de respuesta para garantizar la escalabilidad
 * solicitada por la institución.
 */
class Question extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'points',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones (Entity-Relationship)
    |--------------------------------------------------------------------------
    */

    /**
     * Relación: Una pregunta pertenece a una evaluación (Quiz).
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relación: Una pregunta tiene muchas opciones de respuesta.
     * Útil para tipos 'multiple_choice' y 'true_false'.
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Ayudantes de Lógica
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si la pregunta es de selección múltiple.
     */
    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple_choice';
    }
}
