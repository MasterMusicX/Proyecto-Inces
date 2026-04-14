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
    Schema::create('knowledge_bases', function (Blueprint $table) {
        $table->id();
        $table->string('question');
        $table->text('answer');
        
        // 👇 ESTA ES LA COLUMNA QUE TE FALTA 👇
        $table->string('category')->default('General'); 
        
        $table->integer('views')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};
