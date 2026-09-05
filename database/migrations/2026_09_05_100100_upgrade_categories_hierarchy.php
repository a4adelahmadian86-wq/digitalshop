<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'parent_id')) $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            if (!Schema::hasColumn('categories', 'level')) $table->unsignedTinyInteger('level')->default(0)->after('slug');
            if (!Schema::hasColumn('categories', 'status')) $table->boolean('status')->default(true)->after('is_active');
        });

        Schema::table('categories', function (Blueprint $table) {
            try { $table->index(['parent_id', 'sort_order']); } catch (\Throwable $e) {}
        });

        if (Schema::hasColumn('categories', 'parent_id')) {
            try {
                Schema::table('categories', function (Blueprint $table) {
                    $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
                });
            } catch (\Throwable $e) {}
        }

        if (Schema::hasColumn('categories', 'status')) {
            \DB::table('categories')->update(['status' => \DB::raw('is_active')]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            try { $table->dropForeign(['parent_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['parent_id', 'sort_order']); } catch (\Throwable $e) {}
            if (Schema::hasColumn('categories', 'parent_id')) $table->dropColumn('parent_id');
            if (Schema::hasColumn('categories', 'level')) $table->dropColumn('level');
            if (Schema::hasColumn('categories', 'status')) $table->dropColumn('status');
        });
    }
};
