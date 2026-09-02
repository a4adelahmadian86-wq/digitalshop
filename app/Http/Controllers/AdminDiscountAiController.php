<?php
namespace App\Http\Controllers;
use App\Models\User;use App\Services\AI\CustomerOfferService;use Illuminate\Http\RedirectResponse;
class AdminDiscountAiController extends Controller
{
 public function create(User $user,CustomerOfferService $offers):RedirectResponse{$code=$offers->createFor($user);$user->notify(new \App\Notifications\CustomerDiscountNotification($code));return back()->with('success',"کد اختصاصی {$code->code} با {$code->amount}% تخفیف برای مشتری ساخته و در اعلان‌های او ثبت شد.");}
}
