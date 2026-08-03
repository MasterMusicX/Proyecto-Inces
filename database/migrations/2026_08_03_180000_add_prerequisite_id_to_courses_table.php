<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('courses', 'prerequisite_id')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->foreignId('prerequisite_id')->nullable()->after('category_id')->constrained('courses')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('courses', 'prerequisite_id')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropForeign(['prerequisite_id']);
                $table->dropColumn('prerequisite_id');
            });
        }
    }
};
