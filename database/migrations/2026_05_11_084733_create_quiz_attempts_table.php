<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            
            // Puntuación obtenida
            $table->decimal('score', 5, 2)->nullable()->comment('Nota final del intento');
            
            // Control de tiempo
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // 🔥 El módulo de seguridad (Proctoring) 🔥
            $table->string('proctoring_image')->nullable()->comment('Ruta de la captura de cámara web');
            $table->boolean('suspicious_behavior')->default(false)->comment('Bandera si se detectó cambio de pestaña');

            $table->timestamps();
            
            // Evitar que el mismo estudiante inicie el mismo quiz dos veces (si no está permitido)
            // $table->unique(['quiz_id', 'student_id']); // Descomenta esto si es de intento único
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};