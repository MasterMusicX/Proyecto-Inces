<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'objectives',
        'instructor_id', 'category_id', 'thumbnail',
        'status', 'level', 'duration_hours', 'max_students', 'is_featured'
    ];

    protected function casts(): array {
        return ['is_featured' => 'boolean'];
    }

    protected static function boot() {
        parent::boot();
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . uniqid();
            }
        });
    }

    // 👇 ESTA ES LA FUNCIÓN QUE FALTA PARA ELIMINAR EL ERROR ROJO DE LA URL 👇
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function instructor() { return $this->belongsTo(User::class, 'instructor_id'); }
    public function category()   { return $this->belongsTo(Category::class); }
    
    // 👇 CORREGIDO: Cambiamos 'order' por 'sort_order' para que coincida con tu BD 👇
    public function modules()    { return $this->hasMany(Module::class)->orderBy('sort_order'); }
    
    public function resources()  { return $this->hasMany(Resource::class); }
    public function enrollments(){ return $this->hasMany(Enrollment::class); }
    public function students()   {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'progress_percentage', 'completed_at')
            ->withTimestamps();
    }

    public function getThumbnailUrlAttribute(): string {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('images/default-course.png');
    }
    
    public function getEnrolledCountAttribute(): int {
        return $this->enrollments()->count();
    }
    
    public function isPublished(): bool { return $this->status === 'published'; }

    public function getLevelLabelAttribute(): string {
        return match($this->level) {
            // Dejé los de inglés por si acaso, y agregué los de español que usa tu BD ahora
            'beginner'     => 'Básico',
            'intermediate' => 'Intermedio',
            'advanced'     => 'Avanzado',
            'basico'       => 'Básico',
            'intermedio'   => 'Intermedio',
            'avanzado'     => 'Avanzado',
            default        => 'Básico',
        };
    }
}