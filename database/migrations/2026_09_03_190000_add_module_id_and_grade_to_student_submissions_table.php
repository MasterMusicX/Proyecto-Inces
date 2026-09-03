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
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained('modules')->onDelete('set null');
            $table->decimal('grade', 5, 2)->nullable()->after('status');
            $table->decimal('max_grade', 5, 2)->default(20.00)->after('grade');
            $table->json('skill_rubric')->nullable()->after('max_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id', 'grade', 'max_grade', 'skill_rubric']);
        });
    }
};
