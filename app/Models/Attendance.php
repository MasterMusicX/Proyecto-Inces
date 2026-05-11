<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Attendance (Asistencia)
 * * Gestiona el registro de asistencias diarias de los participantes
 * en las formaciones del INCES Campus, incluyendo el manejo de justificativos médicos o legales.
 */
class Attendance extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'student_id',
        'date',
        'status',                 // present, absent, justified
        'justification_file',     // Ruta del archivo PDF/JPG subido por el estudiante
        'justification_reason',   // Texto explicativo del estudiante
        'justification_status',   // pending, approved, rejected
    ];

    /**
     * Define los "Casts" o conversiones automáticas de tipos de datos.
     * Unificado bajo el estándar de Laravel 11.
     *
     * @return array<string, string>
     */
    protected function casts(): array 
    {
        return [
            'date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones de Base de Datos (Entity-Relationship)
    |--------------------------------------------------------------------------
    */

    /**
     * Relación: Una asistencia pertenece a una formación (Curso) específica.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relación: Una asistencia pertenece a un participante (User) específico.
     * Se especifica 'student_id' porque el nombre de la relación no coincide exactamente con la tabla users.
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
     * Verifica si la falta está justificada y aprobada por el MTP.
     */
    public function isJustified(): bool
    {
        return $this->status === 'justified' && $this->justification_status === 'approved';
    }

    /**
     * Retorna la ruta completa del archivo de justificación (si existe).
     */
    public function getJustificationFileUrlAttribute(): ?string 
    {
        return $this->justification_file 
            ? asset('storage/' . $this->justification_file) 
            : null;
    }
}
