<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Le agregamos el género a los usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender', 10)->nullable()->after('email'); // Puede ser 'M', 'F'
        });

        // 2. Le agregamos la nota y si aprobó a las inscripciones de los cursos
        Schema::table('enrollments', function (Blueprint $table) {
            $table->decimal('final_grade', 5, 2)->nullable()->after('progress_percentage');
            $table->boolean('is_approved')->default(false)->after('final_grade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['final_grade', 'is_approved']);
        });
    }
};