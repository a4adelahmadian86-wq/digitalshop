<?php
namespace App\Services\AI;
use App\Models\DiscountCode;use App\Models\User;use Illuminate\Support\Str;
class CustomerOfferService
{
 public function createFor(User $user): DiscountCode
 {
  $orders=$user->orders()->whereIn('status',['paid','completed'])->count();$points=(int)$user->club_points;
  $amount=$orders===0?10:($orders<3?12:15);$campaign=$orders===0?'welcome_back':($points>=500?'loyal_customer':'returning_customer');
  $code='AI'.strtoupper(Str::random(8));
  return DiscountCode::create(['code'=>$code,'amount'=>$amount,'is_percent'=>true,'max_uses'=>1,'used_count'=>0,'is_active'=>true,'starts_at'=>now(),'expires_at'=>now()->addDays(5),'target_user_id'=>$user->id,'ai_generated'=>true,'campaign_key'=>$campaign]);
 }
}
