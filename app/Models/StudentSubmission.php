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
        'type',
        'title',
        'notes',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'feedback',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'file_size'   => 'integer',
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
}
