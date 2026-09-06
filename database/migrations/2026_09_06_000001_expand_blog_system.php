<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name',120);
                $table->string('slug',160)->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('blog_tags')) {
            Schema::create('blog_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name',100);
                $table->string('slug',140)->unique();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('blog_post_tag')) {
            Schema::create('blog_post_tag', function (Blueprint $table) {
                $table->foreignId('blog_post_id')->constrained('blog_posts')->cascadeOnDelete();
                $table->foreignId('blog_tag_id')->constrained('blog_tags')->cascadeOnDelete();
                $table->primary(['blog_post_id','blog_tag_id']);
            });
        }
        $columns = [
            'category_id' => fn(Blueprint $t) => $t->foreignId('category_id')->nullable()->after('author_id'),
            'featured_image' => fn(Blueprint $t) => $t->string('featured_image')->nullable()->after('excerpt'),
            'meta_title' => fn(Blueprint $t) => $t->string('meta_title',255)->nullable(),
            'meta_description' => fn(Blueprint $t) => $t->string('meta_description',320)->nullable(),
            'og_image' => fn(Blueprint $t) => $t->string('og_image')->nullable(),
            'views_count' => fn(Blueprint $t) => $t->unsignedBigInteger('views_count')->default(0),
            'reading_time' => fn(Blueprint $t) => $t->unsignedSmallInteger('reading_time')->default(1),
        ];
        foreach ($columns as $name => $definition) if (!Schema::hasColumn('blog_posts',$name)) {
            Schema::table('blog_posts',$definition);
        }
        if (Schema::hasColumn('blog_posts','category_id')) {
            try { Schema::table('blog_posts',fn(Blueprint $t)=>$t->foreign('category_id')->references('id')->on('blog_categories')->nullOnDelete()); } catch (Throwable $e) {}
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blog_posts','category_id')) {
            try { Schema::table('blog_posts',fn(Blueprint $t)=>$t->dropForeign(['category_id'])); } catch (Throwable $e) {}
        }
        foreach (['category_id','featured_image','meta_title','meta_description','og_image','views_count','reading_time'] as $column) if (Schema::hasColumn('blog_posts',$column)) Schema::table('blog_posts',fn(Blueprint $t)=>$t->dropColumn($column));
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
    }
};
