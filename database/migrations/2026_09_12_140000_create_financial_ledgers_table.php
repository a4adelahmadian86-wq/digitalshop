<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('financial_ledgers')) {
            return;
        }

        Schema::create('financial_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->string('event_type', 50);
            $table->string('account_code', 50);
            $table->string('account_name');
            $table->enum('side', ['debit', 'credit']);
            $table->unsignedBigInteger('amount');
            $table->string('currency', 10)->default('IRT');
            $table->nullableMorphs('reference');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('posted_at');
            $table->timestamps();

            $table->index(['event_type', 'posted_at']);
            $table->index(['account_code', 'side']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_ledgers');
    }
};
