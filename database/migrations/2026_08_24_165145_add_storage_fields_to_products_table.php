<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Storage provider used for this product
            $table->string('storage_provider')
                ->default('local')
                ->after('file_path');

            // Real path/key inside the selected storage provider
            $table->string('storage_path')
                ->nullable()
                ->after('storage_provider');

            // Original uploaded filename
            $table->string('original_filename')
                ->nullable()
                ->after('storage_path');

            // File metadata
            $table->unsignedBigInteger('file_size')
                ->nullable()
                ->after('original_filename');

            $table->string('mime_type')
                ->nullable()
                ->after('file_size');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'storage_provider',
                'storage_path',
                'original_filename',
                'file_size',
                'mime_type',
            ]);
        });
    }
};