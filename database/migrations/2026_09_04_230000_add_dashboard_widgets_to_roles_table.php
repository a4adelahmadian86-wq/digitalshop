<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('roles', 'dashboard_widgets')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->json('dashboard_widgets')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('roles', 'dashboard_widgets')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('dashboard_widgets');
            });
        }
    }
};