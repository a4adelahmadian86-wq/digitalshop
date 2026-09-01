<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->unsignedBigInteger(
                'tax'
            )->default(0)->after('discount');

            $table->unsignedBigInteger(
                'wallet_amount'
            )->default(0)->after('total');

            $table->unsignedBigInteger(
                'gateway_amount'
            )->default(0)->after('wallet_amount');

            $table->string(
                'payment_method'
            )->nullable()->after('gateway_amount');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'tax',
                'wallet_amount',
                'gateway_amount',
                'payment_method',
            ]);

        });
    }
};