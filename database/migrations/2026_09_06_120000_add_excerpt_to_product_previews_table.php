<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_previews') || Schema::hasColumn('product_previews', 'excerpt')) return;
        Schema::table('product_previews', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('page_limit');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_previews') || !Schema::hasColumn('product_previews', 'excerpt')) return;
        Schema::table('product_previews', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
    }
};
