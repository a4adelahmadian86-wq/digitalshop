<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->foreignId('storage_provider_id')
                ->nullable()
                ->after('category_id')
                ->constrained('storage_providers')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropForeign([
                'storage_provider_id'
            ]);

            $table->dropColumn([
                'storage_provider_id',
            ]);

        });
    }
};