<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Quiz (Evaluación)
 * * Gestiona la configuración de los exámenes dentro de una formación.
 * Controla parámetros críticos como tiempo, intentos y nota aprobatoria
 * para asegurar la integridad académica del INCES Campus.
 */
class Quiz extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'time_limit',
        'passing_score',
        'max_attempts',
        'is_active',
    ];

    /**
     * Conversiones automáticas de tipos de datos (Laravel 11 style).
     */
    protected function casts(): array 
    {
        return [
            'is_active'     => 'boolean',
            'passing_score' => 'decimal:2',
            'time_limit'    => 'integer',
            'max_attempts'  => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones (Entity-Relationship)
    |--------------------------------------------------------------------------
    */

    /**
     * Relación: Una evaluación pertenece a un curso específico.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relación: Un examen tiene muchas preguntas.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relación: Un examen puede tener muchos intentos de diferentes estudiantes.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos de Ayuda (Helpers)
    |--------------------------------------------------------------------------
    */

    /**
     * Calcula el puntaje total sumando los puntos de todas las preguntas.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->questions()->sum('points');
    }

    /**
     * Verifica si el examen está disponible para los estudiantes.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}