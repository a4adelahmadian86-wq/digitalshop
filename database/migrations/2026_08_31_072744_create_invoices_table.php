<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('invoice_number')
                ->unique();

            $table->decimal('subtotal', 15, 0);

            $table->decimal('discount', 15, 0)
                ->default(0);

            $table->decimal('tax', 15, 0)
                ->default(0);

            $table->decimal('total', 15, 0);

            $table->decimal('wallet_amount', 15, 0)
                ->default(0);

            $table->decimal('gateway_amount', 15, 0)
                ->default(0);

            $table->string('payment_method')
                ->nullable();

            $table->string('status')
                ->default('paid');

            $table->timestamp('issued_at')
                ->nullable();

            $table->json('metadata')
                ->nullable();

            $table->timestamps();

            $table->index([
                'user_id',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};