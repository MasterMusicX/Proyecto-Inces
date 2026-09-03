<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'module_id',
        'type',
        'title',
        'notes',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'grade',
        'max_grade',
        'skill_rubric',
        'feedback',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at'  => 'datetime',
            'file_size'    => 'integer',
            'grade'        => 'float',
            'max_grade'    => 'float',
            'skill_rubric' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'medical_receipt' => '🩺 Récipe / Justificativo Médico',
            'assignment'      => '📝 Tarea Realizada',
            default           => '📄 Documento PDF',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            default    => 'Pendiente de Revisión',
        };
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return 'Desconocido';
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Retorna la nota formateada (Ej: 18.50 / 20 pts)
     */
    public function getFormattedGradeAttribute(): string
    {
        if ($this->grade === null) {
            return 'Sin Calificar';
        }
        $max = $this->max_grade ?? 20;
        return number_format($this->grade, 1) . ' / ' . number_format($max, 0) . ' pts';
    }

    /**
     * Accesor para obtener o construir la matriz de habilidades INCES
     */
    public function getSkillRubricDataAttribute(): array
    {
        $default = [
            'technical_skill' => 4, // Destreza Técnica (1-5)
            'work_quality'    => 4, // Calidad del Trabajo (1-5)
            'safety_standards' => 4, // Cumplimiento de Normas (1-5)
            'innovation'      => 4, // Criterio e Innovación (1-5)
            'badge'           => 'Desempeño Destacado INCES',
        ];

        return is_array($this->skill_rubric) ? array_merge($default, $this->skill_rubric) : $default;
    }
}
