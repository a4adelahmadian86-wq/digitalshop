<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('categories', 'level')) {
                $table->unsignedTinyInteger('level')->default(0)->after('slug');
            }
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('categories', 'status')) {
                $table->boolean('status')->default(true)->after('sort_order');
            }
        });

        if (Schema::hasColumn('categories', 'parent_id') && Schema::hasColumn('categories', 'sort_order')) {
            try {
                Schema::table('categories', fn (Blueprint $table) => $table->index(['parent_id', 'sort_order'], 'categories_parent_sort_index'));
            } catch (\Throwable $e) {
                // Index may already exist; schema is still valid.
            }
        }

        if (Schema::hasColumn('categories', 'parent_id')) {
            try {
                Schema::table('categories', function (Blueprint $table) {
                    $table->foreign('parent_id', 'categories_parent_id_foreign')
                        ->references('id')->on('categories')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // Foreign key may already exist.
            }
        }

        if (Schema::hasColumn('categories', 'status') && Schema::hasColumn('categories', 'is_active')) {
            DB::table('categories')->update(['status' => DB::raw('is_active')]);
        }

        if (Schema::hasColumn('categories', 'level') && Schema::hasColumn('categories', 'parent_id')) {
            DB::table('categories')->whereNull('parent_id')->update(['level' => 0]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            try { $table->dropForeign('categories_parent_id_foreign'); } catch (\Throwable $e) {}
            try { $table->dropIndex('categories_parent_sort_index'); } catch (\Throwable $e) {}
            foreach (['parent_id', 'level', 'status'] as $column) {
                if (Schema::hasColumn('categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};