<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Full-text search index on document_analyses
        DB::statement("
            ALTER TABLE document_analyses
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('spanish', coalesce(summary, '') || ' ' || coalesce(extracted_text, ''))
            ) STORED
        ");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_doc_analysis_search ON document_analyses USING gin(search_vector)");

        // Indexes for performance
        Schema::table('courses', function (Blueprint $table) {
            // Le quitamos el 'is_featured' fantasma y dejamos solo 'status'
            $table->index('status');
            $table->index('instructor_id');
        });
        
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
        });
        
        Schema::table('resources', function (Blueprint $table) {
            $table->index(['course_id', 'is_visible']);
            $table->index('type');
        });
        
        Schema::table('chatbot_messages', function (Blueprint $table) {
            $table->index('conversation_id');
        });
    }

    public function down(): void {
        DB::statement("DROP INDEX IF EXISTS idx_doc_analysis_search");
    }
};