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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            
            // Relación con la tabla quizzes
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            
            // Contenido de la pregunta
            $table->text('question_text')->comment('El enunciado de la pregunta');
            
            // Tipo de pregunta (Brutal para la escalabilidad que pide el tutor)
            $table->enum('type', ['multiple_choice', 'true_false', 'open'])->default('multiple_choice')->comment('Tipo de pregunta');
            
            // Puntuación
            $table->integer('points')->default(1)->comment('Valor en puntos de la pregunta');
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (Eliminar la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};