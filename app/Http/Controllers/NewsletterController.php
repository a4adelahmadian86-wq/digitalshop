<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class NewsletterController extends Controller
{
 public function subscribe(Request $request){$data=$request->validate(['email'=>['required','email','max:190']],['email.required'=>'ایمیل را وارد کنید.','email.email'=>'ایمیل معتبر نیست.']);DB::table('newsletter_subscribers')->updateOrInsert(['email'=>strtolower(trim($data['email']))],['user_id'=>auth()->id(),'is_active'=>true,'unsubscribed_at'=>null,'confirmed_at'=>now(),'updated_at'=>now(),'created_at'=>now()]);return back()->with('newsletter_success','عضویت شما با موفقیت ثبت شد.');}
}