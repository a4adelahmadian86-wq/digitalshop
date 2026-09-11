<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('user_ai_memories')) {
            return;
        }

        Schema::create('user_ai_memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category',40);
            $table->string('memory_key',120);
            $table->text('memory_value');
            $table->unsignedTinyInteger('importance')->default(50);
            $table->string('source',40)->nullable();
            $table->boolean('persistent')->default(true);
            $table->timestamp('last_confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id','category','memory_key']);
            $table->index(['user_id','persistent','importance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ai_memories');
    }
};
