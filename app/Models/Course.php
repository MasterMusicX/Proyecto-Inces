<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modelo Course
 * * Representa una formación o curso dentro del ecosistema del INCES Campus.
 * Implementa SoftDeletes para garantizar la integridad referencial y auditoría
 * de los datos institucionales (no se borra físicamente de la base de datos).
 */
class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'slug', 'description', 'objectives',
        'instructor_id', 'category_id', 'prerequisite_id', 'thumbnail',
        'status', 'level', 'duration_hours', 'max_students', 
        'is_featured', 'start_date', 'end_date',
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
            'is_featured' => 'boolean',
            'start_date'  => 'date',
            'end_date'    => 'date',
        ];
    }

    /**
     * Método de arranque (boot) del modelo.
     * Se ejecuta automáticamente al realizar acciones sobre el modelo.
     */
    protected static function boot() 
    {
        parent::boot();
        
        // Antes de crear el curso en la base de datos, genera una URL amigable (slug)
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . uniqid();
            }
        });
    }

    /**
     * Indica a Laravel que utilice el campo 'slug' (y no el ID) para las rutas.
     * Ejemplo: incescampus.com/cursos/electricidad-basica
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones de Base de Datos (Entity-Relationship)
    |--------------------------------------------------------------------------
    */

    /**
     * Relación: Un curso pertenece a un Maestro Técnico Productivo (Instructor).
     */
    public function instructor() { return $this->belongsTo(User::class, 'instructor_id'); }
    
    /**
     * Relación: Un curso pertenece a una categoría u ocupación productiva.
     */
    public function category()   { return $this->belongsTo(Category::class); }

    /**
     * Relación: Un curso puede tener un curso prelación (prerrequisito).
     */
    public function prerequisite() { return $this->belongsTo(Course::class, 'prerequisite_id'); }

    /**
     * Relación: Un curso tiene muchos módulos, ordenados por su campo 'sort_order'.
     */
    public function modules()    { return $this->hasMany(Module::class)->orderBy('sort_order'); }
    
    /**
     * Relación: Un curso tiene muchos recursos didácticos.
     */
    public function resources()  { return $this->hasMany(Resource::class); }
    
    /**
     * Relación: Un curso tiene muchas inscripciones (matrículas).
     */
    public function enrollments(){ return $this->hasMany(Enrollment::class); }

    /**
     * Relación: Un curso tiene una evaluación final (Quiz).
     */
    public function quiz()       { return $this->hasOne(Quiz::class); }

    /**
     * Relación: Un curso puede tener evaluaciones asociadas.
     */
    public function quizzes()    { return $this->hasMany(Quiz::class); }
    public function submissions(){ return $this->hasMany(StudentSubmission::class); }
    
    /**
     * Relación: Un curso tiene muchas asistencias registradas.
     */
    public function attendances(){ return $this->hasMany(Attendance::class); }

    /**
     * Relación Muchos a Muchos: Un curso tiene muchos estudiantes a través de la tabla pivote 'enrollments'.
     * Retorna datos extra de la relación como el progreso y la fecha de finalización.
     */
    public function students()   {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'progress_percentage', 'completed_at')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores y Métodos de Ayuda (Helpers)
    |--------------------------------------------------------------------------
    */
/**
     * Accesor: Obtiene la ruta absoluta de la imagen de portada del curso.
     * Si no tiene imagen, retorna una por defecto.
     * Nota: El signo de interrogación (?string) permite que devuelva un texto o un valor nulo.
     */
    public function getThumbnailUrlAttribute(): ?string 
    {
        // Usamos $this->thumbnail porque así está en tu $fillable y BD
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        
        // Si no hay imagen, devolvemos null sin que PHP dé error
        return null;
    }
    
    /**
     * Accesor: Calcula la cantidad de participantes inscritos actualmente.
     */
    public function getEnrolledCountAttribute(): int {
        return $this->enrollments()->count();
    }
    
    /**
     * Verifica si el curso está disponible públicamente.
     */
    public function isPublished(): bool { 
        return $this->status === 'published'; 
    }

    /**
     * Accesor: Retorna la etiqueta del nivel del curso en español.
     * Maneja compatibilidad con registros antiguos en inglés.
     */
    public function getLevelLabelAttribute(): string {
        return match($this->level) {
            'beginner', 'basico'     => 'Básico',
            'intermediate', 'intermedio' => 'Intermedio',
            'advanced', 'avanzado'   => 'Avanzado',
            default                  => 'Básico',
        };
    }
}