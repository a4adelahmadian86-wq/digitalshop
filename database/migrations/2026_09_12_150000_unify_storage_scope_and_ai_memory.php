<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('storage_providers')) {
            Schema::table('storage_providers', function (Blueprint $table) {
                if (! Schema::hasColumn('storage_providers', 'scope')) {
                    $table->string('scope', 32)->default('files')->after('type');
                }
                if (! Schema::hasColumn('storage_providers', 'location')) {
                    $table->string('location', 16)->default('external')->after('scope');
                }
                if (! Schema::hasColumn('storage_providers', 'priority')) {
                    $table->unsignedInteger('priority')->default(100)->after('location');
                }
                if (! Schema::hasColumn('storage_providers', 'documentation_url')) {
                    $table->string('documentation_url')->nullable();
                }
                if (! Schema::hasColumn('storage_providers', 'capabilities')) {
                    $table->json('capabilities')->nullable();
                }
            });
        }

        if (! Schema::hasTable('user_ai_memories')) {
            Schema::create('user_ai_memories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('category', 64)->default('preference');
                $table->string('memory_key');
                $table->text('memory_value');
                $table->unsignedTinyInteger('importance')->default(50);
                $table->string('source', 64)->default('system');
                $table->boolean('persistent')->default(true);
                $table->timestamp('last_confirmed_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'memory_key']);
            });
        }

        if (! Schema::hasTable('financial_ledgers')) {
            Schema::create('financial_ledgers', function (Blueprint $table) {
                $table->id();
                $table->string('entry_type', 32)->default('posting');
                $table->string('account', 64);
                $table->unsignedBigInteger('debit')->default(0);
                $table->unsignedBigInteger('credit')->default(0);
                $table->string('currency', 8)->default('IRR');
                $table->string('reference')->nullable();
                $table->string('description')->nullable();
                $table->nullableMorphs('ledgerable');
                $table->json('meta')->nullable();
                $table->timestamp('posted_at')->nullable();
                $table->timestamps();
                $table->index(['account', 'posted_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_ledgers');
        Schema::dropIfExists('user_ai_memories');
    }
};
