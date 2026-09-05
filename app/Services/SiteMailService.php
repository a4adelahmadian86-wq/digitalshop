<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SiteMailService
{
    public function configured(): bool
    {
        return (bool) SiteSetting::getValue('mail.enabled', false) && (bool) SiteSetting::getValue('mail.host') && (bool) SiteSetting::getValue('mail.username') && (bool) SiteSetting::getValue('mail.from_address');
    }

    public function send(string $to, string $subject, string $body): void
    {
        abort_unless($this->configured(), 503, 'سرویس ایمیل سایت هنوز در پنل مدیریت تنظیم نشده است.');
        $this->configure();
        Mail::mailer('smtp')->raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function configure(): void
    {
        $password=(string)SiteSetting::getValue('mail.password','');
        try { $password=Crypt::decryptString($password); } catch (\Throwable $e) {}
        config([
            'mail.mailers.smtp.transport'=>'smtp',
            'mail.mailers.smtp.host'=>(string)SiteSetting::getValue('mail.host',''),
            'mail.mailers.smtp.port'=>(int)SiteSetting::getValue('mail.port',587),
            'mail.mailers.smtp.username'=>(string)SiteSetting::getValue('mail.username',''),
            'mail.mailers.smtp.password'=>$password,
            'mail.mailers.smtp.encryption'=>SiteSetting::getValue('mail.encryption','tls') ?: null,
            'mail.from.address'=>(string)SiteSetting::getValue('mail.from_address',''),
            'mail.from.name'=>(string)SiteSetting::getValue('mail.from_name','فایل‌مارکت'),
        ]);
        Mail::purge('smtp');
    }
}
