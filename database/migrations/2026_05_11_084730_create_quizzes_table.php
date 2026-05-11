<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (Crear la tabla).
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            
            // Relación con la tabla cursos
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            
            // Información básica de la evaluación
            $table->string('title')->comment('Título del examen o quiz');
            $table->text('description')->nullable()->comment('Instrucciones para el estudiante');
            
            // Reglas de Negocio (Control académico para el INCES)
            $table->integer('time_limit')->default(30)->comment('Tiempo límite en minutos para resolverlo');
            $table->decimal('passing_score', 5, 2)->default(10.00)->comment('Nota mínima para aprobar');
            $table->integer('max_attempts')->default(1)->comment('Cantidad de intentos permitidos');
            
            // Control de visibilidad
            $table->boolean('is_active')->default(false)->comment('Interruptor del MTP para mostrar/ocultar'); 
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (Eliminar la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};