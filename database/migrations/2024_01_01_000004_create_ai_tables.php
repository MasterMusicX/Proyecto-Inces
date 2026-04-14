<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('session_id')->index();
            $table->string('title')->nullable();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chatbot_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->text('content');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('chatbot_messages');
        Schema::dropIfExists('chatbot_conversations');
    }
};
