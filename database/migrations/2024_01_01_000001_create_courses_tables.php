<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categorías de cursos
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Cursos
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('objectives')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('level', ['basico', 'intermedio', 'avanzado'])->default('basico');
            $table->integer('duration_hours')->default(0)->comment('Duración estimada en horas');
            $table->integer('max_students')->nullable()->comment('Cupo máximo, null = ilimitado');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->default(0);
            $table->json('tags')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_featured')->default(false);
        });

        // Módulos de cursos
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        // Lecciones dentro de módulos
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_free_preview')->default(false);
            $table->timestamps();
        });

        // Inscripciones
       Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            
            // ¡Estas son las tres columnas que te está pidiendo el sistema!
            $table->string('status')->default('active'); // Puede ser active, completed, etc.
            $table->integer('progress_percentage')->default(0); 
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });

        // Progreso por lección
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_categories');
    }
};
