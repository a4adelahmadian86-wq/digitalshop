<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_verifications', function (Blueprint $table) {

            $table->id();

            $table->string('phone', 20)
                ->index();

            $table->string('purpose', 50)
                ->index();

            $table->string('code_hash');

            $table->timestamp('expires_at');

            $table->timestamp('verified_at')
                ->nullable();

            $table->unsignedTinyInteger('attempts')
                ->default(0);

            $table->timestamp('last_sent_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'phone',
                'purpose',
                'expires_at'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};