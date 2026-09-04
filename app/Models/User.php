<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable{
 use Notifiable;protected $fillable=['phone','first_name','last_name','avatar','email','national_code','phone_verified_at','national_code_verified_at','password','role','is_active'];protected $hidden=['password','remember_token'];protected $appends=['avatar_url'];protected function casts():array{return ['phone_verified_at'=>'datetime','national_code_verified_at'=>'datetime','is_active'=>'boolean','password'=>'hashed'];}
 public function getAvatarUrlAttribute():?string{return $this->avatar?Storage::url($this->avatar):null;}
 public function orders():HasMany{return $this->hasMany(Order::class);}public function wallet():HasOne{return $this->hasOne(Wallet::class);}public function walletTopups():HasMany{return $this->hasMany(WalletTopup::class);}public function roles():BelongsToMany{return $this->belongsToMany(Role::class);}public function supportTickets():HasMany{return $this->hasMany(SupportTicket::class);}public function readerSubscriptions():HasMany{return $this->hasMany(ReaderSubscription::class);}public function activeReaderSubscription():HasOne{return $this->hasOne(ReaderSubscription::class)->where('status','active')->where('ends_at','>',now())->latestOfMany();}
 public function hasRole(string $role):bool{return $this->roles()->where('name',$role)->exists()||$this->role===$role;}
 public function hasPermission(string $permission):bool{return $this->roles()->whereHas('permissions',fn($q)=>$q->where('name',$permission))->exists();}
 public function syncSystemRole():void{if(!$this->role)return;$role=Role::where('name',$this->role)->first();if($role)$this->roles()->syncWithoutDetaching([$role->id]);}
}
