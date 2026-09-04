<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void{if(Schema::hasTable('reader_subscriptions'))return;Schema::create('reader_subscriptions',function(Blueprint $table){$table->id();$table->foreignId('user_id')->constrained()->cascadeOnDelete();$table->string('plan',50)->default('monthly');$table->unsignedBigInteger('price')->default(99000);$table->dateTime('starts_at');$table->dateTime('ends_at');$table->string('status',30)->default('active');$table->json('metadata')->nullable();$table->timestamps();$table->index(['user_id','status','ends_at']);});}
 public function down():void{Schema::dropIfExists('reader_subscriptions');}
};