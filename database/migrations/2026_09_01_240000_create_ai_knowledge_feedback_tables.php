<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_product_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_file_id')->nullable()->constrained('product_files')->nullOnDelete();
            $table->string('status', 30)->default('pending');
            $table->unsignedInteger('text_length')->default(0);
            $table->unsignedInteger('chunk_count')->default(0);
            $table->string('source_hash', 64)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('indexed_at')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'status']);
        });

        Schema::create('ai_product_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('ai_product_documents')->cascadeOnDelete();
            $table->unsignedInteger('chunk_no');
            $table->text('content');
            $table->string('content_hash', 64);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['document_id', 'chunk_no']);
            $table->index(['product_id', 'content_hash']);
        });

        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('session_id', 100)->nullable();
            $table->string('message_id', 100)->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('type', 40)->default('response');
            $table->text('comment')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index(['type', 'rating']);
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feedback');
        Schema::dropIfExists('ai_product_chunks');
        Schema::dropIfExists('ai_product_documents');
    }
};
