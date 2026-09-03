<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration{public function up():void{Schema::table('users',function(Blueprint $table){$table->unsignedInteger('club_points')->default(0);$table->string('club_tier',30)->default('member');});}public function down():void{Schema::table('users',function(Blueprint $table){$table->dropColumn(['club_points','club_tier']);});}};
