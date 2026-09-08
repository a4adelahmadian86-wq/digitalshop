<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\DigitalShopNotification;
use App\Services\AI\UserAiMemoryService;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendToRole(string|array $roles,string $category,string $title,string $message,?string $url=null,?string $actionLabel=null,array $meta=[]):int{$roles=(array)$roles;$users=User::query()->where('is_active',true)->whereIn('role',$roles)->get();if($users->isEmpty())return 0;Notification::send($users,new DigitalShopNotification($category,$title,$message,$url,$actionLabel,$meta));return$users->count();}
    public function sendToUser(User $user,string $category,string $title,string $message,?string $url=null,?string $actionLabel=null,array $meta=[]):void{if(!$user->is_active)return;$user->notify(new DigitalShopNotification($category,$title,$message,$url,$actionLabel,$meta));}
    public function sendDailyRecommendations(UserAiMemoryService $memoryService):int
    {
        $sent=0;
        User::query()->where('is_active',true)->where('role','buyer')->chunkById(200,function($users)use(&$sent,$memoryService){foreach($users as $user){$existing=$user->notifications()->where('type',DigitalShopNotification::class)->whereDate('created_at',now()->toDateString())->get()->filter(fn($n)=>data_get($n->data,'category')==='promotion'&&data_get($n->data,'meta.kind')==='daily_product_recommendation');$remaining=max(0,3-$existing->count());if($remaining===0)continue;$owned=Order::query()->where('user_id',$user->id)->whereIn('status',['paid','completed'])->with('items:id,order_id,product_id')->get()->flatMap(fn($o)=>$o->items->pluck('product_id'))->unique()->values();$keywords=$memoryService->keywords($user);$base=Product::query()->where('is_published',true)->when($owned->isNotEmpty(),fn($q)=>$q->whereNotIn('id',$owned));$products=collect();if($keywords){$personalized=clone$base;$products=$personalized->where(function($q)use($keywords){foreach($keywords as $word)$q->orWhere('title','like','%'.$word.'%')->orWhere('short_description','like','%'.$word.'%')->orWhere('description','like','%'.$word.'%');})->latest()->limit($remaining)->get();}$fallback=clone$base;$products=$products->concat($fallback->latest()->limit(max(0,$remaining-$products->count()))->get())->unique('id')->take($remaining);foreach($products as $product){$user->notify(new DigitalShopNotification('promotion','پیشنهاد امروز برای شما','محصول «'.$product->title.'» را ببینید؛ شاید برای شما مناسب باشد.',route('product.show',$product),'مشاهده محصول',['kind'=>'daily_product_recommendation','product_id'=>$product->id,'date'=>now()->toDateString()]));$sent++;}}});return$sent;
    }
}
