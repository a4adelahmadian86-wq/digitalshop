<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('label', 150);
            $table->string('group_name', 60)->index();
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role', 30);
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role', 'permission_id']);
            $table->index('role');
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 100)->index();
            $table->string('target_type', 120)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('changes')->nullable();
            $table->string('outcome', 30)->default('success')->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
};