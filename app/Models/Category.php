<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // 👇 ESTA ES LA LÍNEA MÁGICA QUE ARREGLA EL ERROR 👇
    protected $table = 'course_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}