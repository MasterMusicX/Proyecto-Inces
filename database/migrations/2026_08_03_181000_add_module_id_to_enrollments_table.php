<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('enrollments', 'module_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->foreignId('module_id')->nullable()->after('course_id')->constrained('modules')->nullOnDelete();
                $table->string('enrollment_type')->default('full')->after('module_id'); // 'full' (Curso Completo) o 'module' (Módulo Específico)
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('enrollments', 'module_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropForeign(['module_id']);
                $table->dropColumn(['module_id', 'enrollment_type']);
            });
        }
    }
};
