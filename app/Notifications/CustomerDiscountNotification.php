<?php
namespace App\Notifications;
use App\Models\DiscountCode;use Illuminate\Bus\Queueable;use Illuminate\Contracts\Queue\ShouldQueue;use Illuminate\Notifications\Notification;
class CustomerDiscountNotification extends Notification implements ShouldQueue
{use Queueable;public function __construct(public DiscountCode $code){}public function via($notifiable):array{return ['database'];}public function toArray($notifiable):array{return ['type'=>'customer_offer','title'=>'یک پیشنهاد اختصاصی برای شما','message'=>"کد {$this->code->code} با {$this->code->amount}% تخفیف تا {$this->code->expires_at?->format('Y/m/d')} معتبر است.",'discount_code'=>$this->code->code,'discount_id'=>$this->code->id];}}
