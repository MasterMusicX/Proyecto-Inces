<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
        $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
        $table->date('date');
        
        // Estado de la asistencia
        $table->enum('status', ['present', 'absent', 'justified'])->default('present');
        
        // Sistema de Justificativos
        $table->string('justification_file')->nullable()->comment('Ruta del PDF o imagen médica');
        $table->text('justification_reason')->nullable();
        $table->enum('justification_status', ['pending', 'approved', 'rejected'])->nullable();
        
        $table->timestamps();
        
        // Evitar que el MTP pase asistencia dos veces al mismo chamo el mismo día
        $table->unique(['course_id', 'student_id', 'date']);
    });
}
};
