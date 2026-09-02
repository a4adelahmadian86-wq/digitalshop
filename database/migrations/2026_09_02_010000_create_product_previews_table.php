<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_previews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_file_id')->constrained('product_files')->cascadeOnDelete();
            $table->foreignId('storage_provider_id')->nullable()->constrained('storage_providers')->nullOnDelete();
            $table->string('stored_path');
            $table->unsignedInteger('page_limit')->default(7);
            $table->string('source_sha256', 64);
            $table->timestamps();
            $table->unique(['product_file_id', 'source_sha256']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_previews');
    }
};
