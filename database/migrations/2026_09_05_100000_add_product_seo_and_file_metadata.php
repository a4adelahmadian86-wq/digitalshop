<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'seo_keywords')) $table->text('seo_keywords')->nullable()->after('description');
            if (!Schema::hasColumn('products', 'meta_title')) $table->string('meta_title', 255)->nullable()->after('seo_keywords');
            if (!Schema::hasColumn('products', 'meta_description')) $table->string('meta_description', 320)->nullable()->after('meta_title');
            if (!Schema::hasColumn('products', 'file_format')) $table->string('file_format', 20)->nullable()->after('meta_description');
            if (!Schema::hasColumn('products', 'page_count')) $table->unsignedInteger('page_count')->nullable()->after('file_format');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['seo_keywords','meta_title','meta_description','file_format','page_count'] as $column) {
                if (Schema::hasColumn('products', $column)) $table->dropColumn($column);
            }
        });
    }
};
