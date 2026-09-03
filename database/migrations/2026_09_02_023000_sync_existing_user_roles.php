<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Support\Facades\DB;
return new class extends Migration{public function up():void{foreach(DB::table('users')->whereNotNull('role')->get(['id','role']) as $user){$roleId=DB::table('roles')->where('name',$user->role)->value('id');if($roleId)DB::table('role_user')->insertOrIgnore(['role_id'=>$roleId,'user_id'=>$user->id]);}DB::table('users')->whereIn('role',['admin','seller'])->whereNull('phone_verified_at')->update(['phone_verified_at'=>now()]);}public function down():void{}};
