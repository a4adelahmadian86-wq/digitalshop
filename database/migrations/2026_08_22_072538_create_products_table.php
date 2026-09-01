<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->unsignedBigInteger('price')->default(0);

            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();

            $table->string('thumbnail')->nullable();

            $table->boolean('is_published')->default(false);

            $table->unsignedInteger('downloads_count')->default(0);
            $table->unsignedInteger('sales_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
