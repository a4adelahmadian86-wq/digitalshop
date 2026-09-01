<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('ai_status')->default('not_checked')->after('is_published');
            $table->unsignedTinyInteger('ai_score')->nullable()->after('ai_status');
            $table->text('ai_summary')->nullable()->after('ai_score');
            $table->json('ai_report')->nullable()->after('ai_summary');
            $table->string('ai_source_hash', 64)->nullable()->after('ai_report');
            $table->timestamp('ai_indexed_at')->nullable()->after('ai_source_hash');
            $table->index(['ai_status', 'is_published']);
        });

        Schema::create('ai_product_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('score')->nullable();
            $table->json('findings')->nullable();
            $table->json('evidence')->nullable();
            $table->string('source_hash', 64)->nullable();
            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'status']);
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->boolean('is_published')->default(true);
            $table->json('ai_topics')->nullable();
            $table->timestamps();
            $table->index(['product_id', 'is_published']);
        });

        Schema::create('ai_user_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('session_id', 100)->nullable();
            $table->string('event', 40);
            $table->string('query')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'event']);
            $table->index(['session_id', 'event']);
            $table->index(['product_id', 'event']);
        });

        Schema::create('ai_user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
            $table->json('interests')->nullable();
            $table->json('search_terms')->nullable();
            $table->json('recent_products')->nullable();
            $table->json('preferences')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_user_profiles');
        Schema::dropIfExists('ai_user_events');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('ai_product_analyses');

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['ai_status', 'is_published']);
            $table->dropColumn([
                'ai_status',
                'ai_score',
                'ai_summary',
                'ai_report',
                'ai_source_hash',
                'ai_indexed_at',
            ]);
        });
    }
};
