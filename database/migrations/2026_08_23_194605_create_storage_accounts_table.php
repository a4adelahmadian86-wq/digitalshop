<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('storage_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 30);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('bucket')->nullable();
            $table->string('endpoint')->nullable();
            $table->unsignedBigInteger('capacity_bytes')->default(0);
            $table->unsignedBigInteger('used_bytes')->default(0);
            $table->text('credentials')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('storage_accounts');
    }
};