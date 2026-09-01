<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('product_questions')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->index(['product_id', 'parent_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_questions');
    }
};
