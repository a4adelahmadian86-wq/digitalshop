<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('storage_provider_id')->constrained('storage_providers')->restrictOnDelete();
            $table->string('original_name');
            $table->string('stored_path');
            $table->string('mime_type', 150);
            $table->string('extension', 10);
            $table->unsignedBigInteger('size');
            $table->string('sha256', 64);
            $table->string('status', 30)->default('uploaded');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index(['sha256']);
        });

        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('storage_provider_id')->constrained('storage_providers')->restrictOnDelete();
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('storage_path');
            $table->string('mime_type', 150);
            $table->string('extension', 10);
            $table->unsignedBigInteger('size');
            $table->string('sha256', 64);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['product_id', 'sort_order']);
            $table->index(['sha256']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_files');
        Schema::dropIfExists('product_uploads');
    }
};
