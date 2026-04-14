<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    // Asegúrate de que 'category' esté aquí adentro
    protected $fillable = [
        'question',
        'answer',
        'category',
        'views',
    ];
}