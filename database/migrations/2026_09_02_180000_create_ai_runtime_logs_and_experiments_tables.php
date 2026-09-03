<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_runtime_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id',100)->nullable();
            $table->string('operation',80);
            $table->string('provider',80)->nullable();
            $table->string('model',120)->nullable();
            $table->string('status',30)->default('success');
            $table->unsignedInteger('latency_ms')->nullable();
            $table->unsignedInteger('input_tokens')->nullable();
            $table->unsignedInteger('output_tokens')->nullable();
            $table->decimal('cost',12,6)->nullable();
            $table->json('request_meta')->nullable();
            $table->json('response_meta')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->index(['operation','created_at']);
            $table->index(['provider','model','created_at']);
        });

        Schema::create('ai_model_experiments', function (Blueprint $table) {
            $table->id();
            $table->string('name',120)->unique();
            $table->string('provider',80);
            $table->string('model',120);
            $table->string('task',80);
            $table->decimal('weight',5,2)->default(1);
            $table->boolean('enabled')->default(false);
            $table->json('config')->nullable();
            $table->json('metrics')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->index(['task','enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_model_experiments');
        Schema::dropIfExists('ai_runtime_logs');
    }
};
