<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\Mail;use Illuminate\Support\Str;
class EmailOnboardingController extends Controller
{
 public function create(){return view('auth.email-onboarding');}
 public function send(Request $request){$data=$request->validate(['email'=>['required','email','max:255']]);$email=strtolower(trim($data['email']));$token=Str::random(64);DB::table('email_verification_tokens')->where('email',$email)->delete();DB::table('email_verification_tokens')->insert(['email'=>$email,'token_hash'=>hash('sha256',$token),'expires_at'=>now()->addMinutes(20),'created_at'=>now()]);$url=route('email.onboarding.verify',['token'=>$token]);try{Mail::raw("برای تأیید ایمیل و ادامه ساخت حساب فایل‌مارکت روی لینک زیر بزنید:\n\n{$url}\n\nاین لینک تا ۲۰ دقیقه معتبر است.",fn($m)=>$m->to($email)->subject('تأیید ایمیل فایل‌مارکت'));}catch(\Throwable $e){report($e);return back()->withErrors(['email'=>'ارسال ایمیل انجام نشد. تنظیمات سرویس ایمیل را بررسی کنید.'])->withInput();}return view('auth.email-sent',compact('email'));}
 public function verify(string $token,Request $request){$row=DB::table('email_verification_tokens')->where('token_hash',hash('sha256',$token))->first();abort_unless($row&&now()->lt($row->expires_at),404);DB::table('email_verification_tokens')->where('email',$row->email)->delete();$request->session()->put(['email_onboarding.verified'=>true,'email_onboarding.email'=>$row->email,'otp.purpose'=>'email_onboarding']);return redirect()->route('email.onboarding.phone');}
 public function phone(){abort_unless(session('email_onboarding.verified')&&session('email_onboarding.email'),403);return view('auth.email-phone',['email'=>session('email_onboarding.email')]);}
}
