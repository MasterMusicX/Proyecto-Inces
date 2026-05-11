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
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            
            // Relación explícita con la tabla questions
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            
            // Texto de la opción (Se usa text para respuestas largas)
            $table->text('option_text')->comment('Texto de la posible respuesta');
            
            // Bandera para saber si esta es la respuesta correcta
            $table->boolean('is_correct')->default(false)->comment('True si es la respuesta correcta, False si es distractora');
            
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones (Eliminar la tabla).
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};