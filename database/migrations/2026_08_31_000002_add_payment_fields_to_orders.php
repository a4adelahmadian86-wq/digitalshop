<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('payment_authority')
                ->nullable()
                ->after('payment_method');

            $table->string('payment_ref_id')
                ->nullable()
                ->after('payment_authority');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'payment_authority',
                'payment_ref_id',
            ]);

        });
    }
};