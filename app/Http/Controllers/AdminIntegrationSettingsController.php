<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AdminIntegrationSettingsController extends Controller
{
    public function edit(){abort_unless(auth()->user()?->hasPermission('integrations.manage'),403);return view('admin.settings.integrations',['mail'=>['enabled'=>(bool)SiteSetting::getValue('mail.enabled',false),'host'=>SiteSetting::getValue('mail.host',''),'port'=>SiteSetting::getValue('mail.port',587),'username'=>SiteSetting::getValue('mail.username',''),'encryption'=>SiteSetting::getValue('mail.encryption','tls'),'from_address'=>SiteSetting::getValue('mail.from_address',''),'from_name'=>SiteSetting::getValue('mail.from_name','فایل‌مارکت')],'google'=>['enabled'=>(bool)SiteSetting::getValue('google.enabled',false),'client_id'=>SiteSetting::getValue('google.client_id',''),'client_secret'=>SiteSetting::getValue('google.client_secret','')]]);}

    public function update(Request $request){
        abort_unless(auth()->user()?->hasPermission('integrations.manage'),403);
        $data=$request->validate(['mail.host'=>'nullable|string|max:255','mail.port'=>'nullable|integer|min:1|max:65535','mail.username'=>'nullable|email|max:255','mail.password'=>'nullable|string|max:1000','mail.encryption'=>'nullable|in:tls,ssl,none','mail.from_address'=>'nullable|email|max:255','mail.from_name'=>'nullable|string|max:120','google.client_id'=>'nullable|string|max:1000','google.client_secret'=>'nullable|string|max:2000']);
        SiteSetting::putValue('mail.enabled',$request->boolean('mail.enabled'));SiteSetting::putValue('google.enabled',$request->boolean('google.enabled'));
        foreach(['mail.host','mail.port','mail.username','mail.encryption','mail.from_address','mail.from_name','google.client_id'] as $key)if(array_key_exists($key,$data))SiteSetting::putValue($key,$data[$key]);
        if(!empty($data['mail.password']))SiteSetting::putValue('mail.password',Crypt::encryptString($data['mail.password']));
        if(!empty($data['google.client_secret']))SiteSetting::putValue('google.client_secret',Crypt::encryptString($data['google.client_secret']));
        return back()->with('success','تنظیمات اتصال‌ها ذخیره شد.');
    }

    public function testMail(Request $request,\App\Services\SiteMailService $mail){abort_unless(auth()->user()?->hasPermission('integrations.manage'),403);$data=$request->validate(['email'=>['required','email']]);try{$mail->send($data['email'],'آزمایش ایمیل فایل‌مارکت','این پیام برای آزمایش اتصال ایمیل اختصاصی سایت ارسال شده است.');return back()->with('success','ایمیل آزمایشی ارسال شد.');}catch(\Throwable $e){report($e);return back()->withErrors(['mail'=>'ارسال ایمیل آزمایشی ناموفق بود. تنظیمات SMTP را بررسی کنید.']);}}
}
