<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Recursos educativos
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'pdf', 'docx', 'xlsx', 'pptx',
                'video', 'audio', 'image',
                'url', 'youtube', 'other'
            ]);
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->bigInteger('file_size')->nullable()->comment('Tamaño en bytes');
            $table->string('mime_type')->nullable();
            $table->string('external_url')->nullable();
            $table->json('metadata')->nullable()->comment('Metadatos adicionales del archivo');
            $table->boolean('is_downloadable')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Análisis de documentos con IA
        Schema::create('document_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->text('extracted_text')->nullable()->comment('Texto extraído del documento');
            $table->text('summary')->nullable()->comment('Resumen generado por IA');
            $table->json('keywords')->nullable()->comment('Palabras clave identificadas');
            $table->json('topics')->nullable()->comment('Temas principales');
            $table->json('key_concepts')->nullable()->comment('Conceptos clave');
            $table->text('ai_analysis')->nullable()->comment('Análisis completo de IA');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->integer('processing_time_ms')->nullable();
            $table->timestamps();
        });

        // Interacciones del chatbot
        Schema::create('chatbot_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->index()->comment('ID de sesión de conversación'); // <-- El índice ya se crea aquí
            $table->enum('role', ['user', 'assistant'])->default('user');
            $table->text('message');
            $table->text('response')->nullable();
            $table->json('context')->nullable()->comment('Contexto adicional usado');
            $table->json('sources')->nullable()->comment('Fuentes referenciadas');
            $table->integer('tokens_used')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->boolean('was_helpful')->nullable()->comment('Retroalimentación del usuario');
            $table->timestamp('timestamp');
            $table->timestamps();

            $table->index(['user_id', 'session_id']);
            // $table->index('session_id'); <-- BORRA O COMENTA ESTA LÍNEA QUE SOBRA
        });

        // Consultas de IA (historial de queries)
        Schema::create('ai_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('resource_id')->nullable()->constrained()->nullOnDelete();
            $table->text('question');
            $table->text('response');
            $table->enum('query_type', ['chat', 'document_analysis', 'search', 'summary'])->default('chat');
            $table->integer('tokens_used')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->boolean('was_helpful')->nullable();
            $table->timestamps();
        });

        // Base de conocimiento del chatbot
        Schema::create('knowledge_base', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index();
            $table->string('question');
            $table->text('answer');
            $table->json('tags')->nullable();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->fullText(['question', 'answer'])->language('spanish');
        });

        // Índice de búsqueda semántica
        Schema::create('search_index', function (Blueprint $table) {
            $table->id();
            $table->morphs('indexable');
            $table->text('content');
            $table->json('keywords')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Notificaciones
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Estadísticas de uso
        Schema::create('usage_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action')->index();
            $table->json('data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index('created_at');
        });

        // Cache del sistema
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Jobs queue
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('usage_statistics');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('search_index');
        Schema::dropIfExists('knowledge_base');
        Schema::dropIfExists('ai_queries');
        Schema::dropIfExists('chatbot_interactions');
        Schema::dropIfExists('document_analyses');
        Schema::dropIfExists('resources');
    }
};
