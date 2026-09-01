<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')
                ->constrained('wallets')
                ->cascadeOnDelete();

            /*
             * credit = افزایش
             * debit  = کاهش
             */
            $table->string('type', 20);

            /*
             * مبلغ همیشه مثبت ذخیره می‌شود.
             */
            $table->unsignedBigInteger('amount');

            $table->unsignedBigInteger('balance_before');

            $table->unsignedBigInteger('balance_after');

            /*
             * completed
             * failed
             * reversed
             */
            $table->string('status', 20)
                ->default('completed');

            /*
             * اتصال به Order / WalletTopup / ...
             */
            $table->nullableMorphs('reference');

            $table->string('description')
                ->nullable();

            $table->json('metadata')
                ->nullable();

            $table->timestamps();

            $table->index([
                'wallet_id',
                'created_at',
            ]);

            $table->index([
                'wallet_id',
                'type',
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};