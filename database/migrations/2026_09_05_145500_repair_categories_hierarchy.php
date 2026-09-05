<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) return;

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'parent_id')) $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            if (!Schema::hasColumn('categories', 'level')) $table->unsignedTinyInteger('level')->default(0)->after('slug');
            if (!Schema::hasColumn('categories', 'sort_order')) $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            if (!Schema::hasColumn('categories', 'status')) $table->boolean('status')->default(true)->after('sort_order');
        });

        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->index(['parent_id', 'sort_order'], 'categories_parent_sort_index_v2');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreign('parent_id', 'categories_parent_id_foreign_v2')->references('id')->on('categories')->nullOnDelete();
            });
        } catch (\Throwable $e) {}

        if (Schema::hasColumn('categories', 'status') && Schema::hasColumn('categories', 'is_active')) {
            DB::table('categories')->update(['status' => DB::raw('is_active')]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('categories')) return;
        Schema::table('categories', function (Blueprint $table) {
            try { $table->dropForeign('categories_parent_id_foreign_v2'); } catch (\Throwable $e) {}
            try { $table->dropIndex('categories_parent_sort_index_v2'); } catch (\Throwable $e) {}
        });
    }
};