<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('courses', function (Blueprint $table) {
        $table->date('start_date')->nullable()->after('status');
        $table->date('end_date')->nullable()->after('start_date');
    });
}

public function down()
{
    Schema::table('courses', function (Blueprint $table) {
        $table->dropColumn(['start_date', 'end_date']);
    });
}
};
