<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Option (Opción de Respuesta)
 * * Representa cada una de las alternativas de respuesta asociadas a una pregunta.
 */
class Option extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    /**
     * Define las conversiones automáticas de datos.
     */
    protected function casts(): array 
    {
        return [
            'is_correct' => 'boolean', // Garantiza que siempre sea true o false
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones (Entity-Relationship)
    |--------------------------------------------------------------------------
    */

    /**
     * Relación: Una opción de respuesta pertenece a una pregunta específica.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}