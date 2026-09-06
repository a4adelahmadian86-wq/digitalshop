<?php
namespace App\Services;
use App\Models\User;
class CustomerPlusService
{
 public function status(User $user):array{$since=now()->subDays((int)config('customer_plus.window_days',365));$orders=$user->orders()->whereIn('status',['paid','completed'])->where('created_at','>=',$since)->get(['id','total_amount']);$count=$orders->count();$spend=(float)$orders->sum('total_amount');$pro=$count>=config('customer_plus.pro_orders',10)||$spend>=config('customer_plus.pro_spend',1500000);$plus=$pro||$count>=config('customer_plus.min_orders',5)||$spend>=config('customer_plus.min_spend',500000);$targetOrders=$pro?config('customer_plus.pro_orders',10):config('customer_plus.min_orders',5);$targetSpend=$pro?config('customer_plus.pro_spend',1500000):config('customer_plus.min_spend',500000);return ['active'=>$plus,'level'=>$pro?'plus-pro':($plus?'plus':'member'),'orders'=>$count,'spend'=>$spend,'target_orders'=>$targetOrders,'target_spend'=>$targetSpend,'orders_left'=>max(0,$targetOrders-$count),'spend_left'=>max(0,$targetSpend-$spend),'benefits'=>$pro?['تخفیف اختصاصی سطح ویژه','پیشنهادهای اولویت‌دار','دسترسی زودتر به فایل‌های منتخب','پشتیبانی سریع‌تر']:['پیشنهادهای اختصاصی','تخفیف‌های وفاداری','پیشنهادهای شخصی‌سازی‌شده','اولویت در کمپین‌های ویژه']];}
}
