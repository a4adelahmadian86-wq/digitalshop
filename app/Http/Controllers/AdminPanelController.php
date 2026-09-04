<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminPanelController extends Controller
{
    public function module(Request $request, string $module, ?string $sub = null)
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $titles = [
            'users' => 'کاربران', 'roles' => 'نقش‌ها و دسترسی‌ها', 'products' => 'محصولات',
            'orders' => 'سفارش‌ها', 'finance' => 'مالی', 'subscriptions' => 'اشتراک‌ها',
            'assets' => 'فایل‌ها و دارایی‌ها', 'reader' => 'خوانشگر', 'loyalty' => 'باشگاه مشتریان',
            'content' => 'محتوا', 'blog' => 'وبلاگ', 'pages' => 'صفحات ثابت', 'marketing' => 'بازاریابی',
            'ai' => 'هوش مصنوعی', 'notifications' => 'اعلان‌ها', 'support' => 'پشتیبانی',
            'reports' => 'گزارش‌ها و تحلیل‌ها', 'storage' => 'فضای ذخیره‌سازی', 'settings' => 'تنظیمات',
            'integrations' => 'یکپارچه‌سازی‌ها', 'security' => 'امنیت', 'sellers' => 'فروشندگان',
            'search' => 'جستجو', 'workflow' => 'گردش کار', 'tasks' => 'وظایف', 'backup' => 'پشتیبان‌گیری',
            'system' => 'سلامت و مدیریت سیستم', 'developers' => 'مرکز توسعه‌دهندگان',
            'newsletter' => 'خبرنامه و اطلاع‌رسانی محصولات', 'sms' => 'مدیریت پیامک و رمز یکبارمصرف',
        ];

        $subtitles = [
            'newsletter' => 'ارسال خبر محصولات جدید و پیشنهادهای شخصی‌سازی‌شده بر اساس فعالیت کاربران',
            'sms' => 'آمار ارسال رمز یکبارمصرف، پیامک‌ها، خطاها و محدودیت‌های سرویس',
            'ai' => 'مدیریت مدل‌ها، مصرف، ارزیابی، پرامپت‌ها و سرویس‌های هوش مصنوعی',
            'storage' => 'ظرفیت، مصرف، فایل‌های بزرگ و وضعیت ارائه‌دهندگان ذخیره‌سازی',
            'reports' => 'نمایش شاخص‌های فروش، کاربران، محصولات، مالی، محتوا، هوش مصنوعی و پشتیبانی',
        ];

        $title = $titles[$module] ?? 'مدیریت سیستم';
        $subTitle = $subtitles[$module] ?? 'مدیریت یکپارچه این بخش از سامانه';
        if ($sub) {
            $subMap = [
                'overview'=>'نمای کلی','all'=>'همه موارد','active'=>'موارد فعال','pending'=>'در انتظار بررسی',
                'settings'=>'تنظیمات','transactions'=>'تراکنش‌ها','plans'=>'پلن‌ها','reviews'=>'نظرات',
                'users'=>'کاربران','models'=>'مدل‌ها','providers'=>'ارائه‌دهندگان','evaluation'=>'ارزیابی مدل‌ها',
                'usage'=>'مصرف و اعتبار','tickets'=>'تیکت‌ها','knowledge'=>'پایگاه دانش','daily'=>'گزارش روزانه',
                'monthly'=>'گزارش ماهانه','security'=>'امنیت','logs'=>'گزارش رویدادها','quotas'=>'سهمیه‌ها',
            ];
            $subTitle = $subMap[$sub] ?? $subTitle;
        }

        $stats = [
            ['label'=>'کاربران','value'=>$this->count('users'),'icon'=>'users'],
            ['label'=>'محصولات','value'=>$this->count('products'),'icon'=>'bag'],
            ['label'=>'سفارش‌ها','value'=>$this->count('orders'),'icon'=>'document'],
            ['label'=>'تراکنش‌ها','value'=>$this->count('payments'),'icon'=>'wallet'],
        ];

        if ($module === 'newsletter') {
            $stats = [
                ['label'=>'مشترکان ایمیل','value'=>$this->count('newsletter_subscribers'),'icon'=>'send'],
                ['label'=>'فعال','value'=>$this->countWhere('newsletter_subscribers','is_active',1),'icon'=>'check'],
                ['label'=>'ارسال‌های موفق','value'=>$this->count('newsletter_campaigns'),'icon'=>'notification'],
                ['label'=>'ارسال‌های ناموفق','value'=>$this->countWhere('newsletter_campaigns','status','failed'),'icon'=>'warning'],
            ];
        }
        if ($module === 'sms') {
            $stats = [
                ['label'=>'ارسال رمز یکبارمصرف','value'=>$this->count('otp_verifications'),'icon'=>'send'],
                ['label'=>'امروز','value'=>$this->countToday('otp_verifications'),'icon'=>'calendar'],
                ['label'=>'پیامک‌های موفق','value'=>$this->countWhere('otp_verifications','status','verified'),'icon'=>'check'],
                ['label'=>'ناموفق / منقضی','value'=>$this->countWhereIn('otp_verifications','status',['failed','expired']),'icon'=>'warning'],
            ];
        }

        return view('admin.panel.module', compact('title','subTitle','module','sub','stats'));
    }

    private function count(string $table): int
    {
        return Schema::hasTable($table) ? DB::table($table)->count() : 0;
    }

    private function countWhere(string $table, string $column, $value): int
    {
        return Schema::hasTable($table) && Schema::hasColumn($table,$column) ? DB::table($table)->where($column,$value)->count() : 0;
    }

    private function countWhereIn(string $table, string $column, array $values): int
    {
        return Schema::hasTable($table) && Schema::hasColumn($table,$column) ? DB::table($table)->whereIn($column,$values)->count() : 0;
    }

    private function countToday(string $table): int
    {
        if (!Schema::hasTable($table)) return 0;
        $column = Schema::hasColumn($table,'created_at') ? 'created_at' : null;
        return $column ? DB::table($table)->whereDate($column,now()->toDateString())->count() : 0;
    }
}
