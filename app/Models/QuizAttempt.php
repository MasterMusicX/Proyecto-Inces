<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Modelo QuizAttempt (Intento de Evaluación)
 * * Registra la participación de un estudiante en un examen.
 * Incluye auditoría de tiempo, calificación final y evidencia de seguridad
 * mediante proctoring (captura de imagen y detección de anomalías).
 */
class QuizAttempt extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'quiz_id',
        'student_id',
        'score',
        'started_at',
        'completed_at',
        'proctoring_image',
        'suspicious_behavior',
    ];

    /**
     * Conversiones automáticas de tipos de datos.
     */
    protected function casts(): array 
    {
        return [
            'score'               => 'decimal:2',
            'started_at'          => 'datetime',
            'completed_at'        => 'datetime',
            'suspicious_behavior' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones (Entity-Relationship)
    |--------------------------------------------------------------------------
    */

    /**
     * Relación: Un intento pertenece a una evaluación específica.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relación: Un intento es realizado por un participante (User).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores y Métodos de Ayuda (Helpers)
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica si el intento ya fue finalizado.
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Accesor: Obtiene la ruta absoluta de la imagen capturada por la cámara (Proctoring).
     */
    public function getProctoringImageUrlAttribute(): ?string 
    {
        return $this->proctoring_image 
            ? asset('storage/' . $this->proctoring_image) 
            : null;
    }

    /**
     * Calcula la duración real en minutos que le tomó al estudiante resolver el examen.
     */
    public function getDurationInMinutesAttribute(): int
    {
        if (!$this->started_at || !$this->completed_at) {
            return 0;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }
}