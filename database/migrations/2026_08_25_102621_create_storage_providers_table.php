<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_providers', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            /*
             * local
             * api
             * ftp
             * s3
             * ...
             */
            $table->string('type');

            /*
             * Provider configuration.
             *
             * Examples:
             *
             * {
             *     "disk": "local"
             * }
             *
             * or later:
             *
             * {
             *     "endpoint": "...",
             *     "api_key": "..."
             * }
             */
            $table->json('config')->nullable();

            $table->boolean('is_active')->default(true);

            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_providers');
    }
};