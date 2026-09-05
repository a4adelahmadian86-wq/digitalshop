<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminModuleController extends Controller
{
    private const MODULES = [
        'users'=>'کاربران','products'=>'محصولات','orders'=>'سفارش‌ها','finance'=>'مالی','subscriptions'=>'اشتراک‌ها',
        'assets'=>'فایل‌ها و دارایی‌ها','reader'=>'خوانشگر','loyalty'=>'باشگاه مشتریان','content'=>'محتوا','marketing'=>'بازاریابی',
        'ai'=>'هوش مصنوعی','support'=>'پشتیبانی','notifications'=>'اعلان‌ها','reports'=>'گزارش‌ها و تحلیل‌ها','search'=>'جستجو',
        'sellers'=>'فروشندگان','workflow'=>'گردش کار','tasks'=>'وظایف','security'=>'امنیت','integrations'=>'یکپارچه‌سازی‌ها',
        'settings'=>'تنظیمات','system'=>'سیستم','developer'=>'توسعه‌دهنده','newsletter'=>'خبرنامه محصولات','sms'=>'پیامک و رمز یکبارمصرف',
    ];

    private const TABLES = [
        'users'=>'users','products'=>'products','orders'=>'orders','finance'=>'payments','subscriptions'=>'reader_subscriptions',
        'assets'=>'product_files','reader'=>'reader_subscriptions','loyalty'=>'users','content'=>'blog_posts','marketing'=>'discounts',
        'ai'=>'ai_provider_settings','support'=>'support_tickets','notifications'=>'notifications','reports'=>'orders','search'=>'products',
        'sellers'=>'users','workflow'=>'orders','tasks'=>'users','security'=>'users','integrations'=>'integration_settings',
        'settings'=>'site_settings','system'=>'users','developer'=>'users','newsletter'=>'newsletter_subscribers','sms'=>'otp_verifications',
    ];

    private const SECTIONS = [
        'users'=>['overview'=>'مرکز کاربران','all'=>'همه کاربران','active'=>'کاربران فعال','pending'=>'در انتظار تأیید','roles'=>'نقش‌ها و دسترسی‌ها','groups'=>'گروه‌های کاربری','activity'=>'فعالیت کاربران'],
        'products'=>['overview'=>'مرکز محصولات','all'=>'همه محصولات','create'=>'افزودن محصول','draft'=>'پیش‌نویس‌ها','pending'=>'در انتظار تأیید','approved'=>'تأییدشده','rejected'=>'ردشده','categories'=>'دسته‌بندی‌ها','features'=>'ویژگی‌ها','pricing'=>'قیمت‌گذاری','reviews'=>'نظرات'],
        'orders'=>['overview'=>'مرکز سفارش‌ها','all'=>'همه سفارش‌ها','pending'=>'در انتظار پرداخت','paid'=>'موفق','failed'=>'ناموفق','cancelled'=>'لغوشده','completed'=>'تکمیل‌شده','refunded'=>'استرداد وجه'],
        'finance'=>['overview'=>'مرکز مالی','transactions'=>'تراکنش‌ها','payments'=>'پرداخت‌ها','wallets'=>'کیف پول','withdrawals'=>'برداشت‌ها','settlements'=>'تسویه‌ها','invoices'=>'فاکتورها','commissions'=>'کمیسیون‌ها','taxes'=>'مالیات'],
        'subscriptions'=>['overview'=>'مرکز اشتراک‌ها','subscriptions'=>'اشتراک‌ها','plans'=>'پلن‌ها','renewals'=>'تمدیدها','changes'=>'تغییر پلن'],
        'assets'=>['overview'=>'کتابخانه دارایی‌ها','library'=>'کتابخانه فایل‌ها','folders'=>'پوشه‌ها','mine'=>'فایل‌های من','purchased'=>'فایل‌های خریداری‌شده','storage'=>'فضای ذخیره‌سازی'],
        'reader'=>['overview'=>'مرکز خوانشگر','library'=>'کتابخانه','bookmarks'=>'بوکمارک‌ها','highlights'=>'هایلایت‌ها','notes'=>'یادداشت‌ها','history'=>'تاریخچه مطالعه'],
        'loyalty'=>['overview'=>'مرکز باشگاه مشتریان','customers'=>'مشتریان','points'=>'امتیازات','tiers'=>'سطوح','rewards'=>'جوایز'],
        'content'=>['overview'=>'مرکز محتوا','pages'=>'صفحات','blog'=>'وبلاگ','categories'=>'دسته‌بندی‌ها','media'=>'رسانه','banners'=>'بنرها','reviews'=>'نظرات'],
        'marketing'=>['overview'=>'مرکز بازاریابی','discounts'=>'تخفیف‌ها','campaigns'=>'کمپین‌ها','affiliates'=>'همکاری در فروش','referrals'=>'معرفی دوستان','ads'=>'تبلیغات'],
        'ai'=>['overview'=>'مرکز هوش مصنوعی','models'=>'مدل‌ها','providers'=>'ارائه‌دهندگان','evaluation'=>'ارزیابی مدل‌ها','prompts'=>'پرامپت‌ها','content'=>'تولید محتوا','usage'=>'مصرف و اعتبار','settings'=>'تنظیمات AI'],
        'support'=>['overview'=>'مرکز پشتیبانی','tickets'=>'تیکت‌ها','mine'=>'تیکت‌های من','assignments'=>'ارجاع‌ها','sla'=>'زمان پاسخ و حل','knowledge'=>'پایگاه دانش'],
        'notifications'=>['overview'=>'مرکز اعلان‌ها','all'=>'اعلان‌ها','send'=>'ارسال اعلان','templates'=>'قالب‌ها','campaigns'=>'کمپین‌های اطلاع‌رسانی'],
        'reports'=>['overview'=>'مرکز گزارش‌ها','sales'=>'گزارش فروش','finance'=>'گزارش مالی','users'=>'گزارش کاربران','products'=>'گزارش محصولات','content'=>'گزارش محتوا','ai'=>'گزارش هوش مصنوعی','support'=>'گزارش پشتیبانی'],
        'search'=>['overview'=>'مرکز جستجو','settings'=>'تنظیمات جستجو','analytics'=>'تحلیل جستجو','suggestions'=>'پیشنهادها'],
        'sellers'=>['overview'=>'مرکز فروشندگان','manage'=>'مدیریت فروشندگان','requests'=>'درخواست‌های فروشندگی','performance'=>'عملکرد','commission'=>'کمیسیون'],
        'workflow'=>['overview'=>'مرکز گردش کار','flows'=>'گردش کار','approvals'=>'تأییدها','queues'=>'صف‌های کاری'],
        'tasks'=>['overview'=>'مرکز وظایف','mine'=>'وظایف من','team'=>'وظایف تیم','reminders'=>'یادآوری‌ها'],
        'security'=>['overview'=>'مرکز امنیت','logins'=>'ورودها','sessions'=>'نشست‌ها','events'=>'رخدادهای امنیتی','audit'=>'گزارش حسابرسی'],
        'integrations'=>['overview'=>'مرکز یکپارچه‌سازی','services'=>'سرویس‌ها','api'=>'API','keys'=>'کلیدهای API','webhooks'=>'وب‌هوک‌ها','external'=>'سرویس‌های خارجی'],
        'settings'=>['overview'=>'تنظیمات سامانه','general'=>'عمومی','store'=>'فروشگاه','payment'=>'پرداخت','email'=>'ایمیل','sms'=>'پیامک','notifications'=>'اعلان‌ها','security'=>'امنیت'],
        'system'=>['overview'=>'مرکز سیستم','health'=>'سلامت سیستم','events'=>'گزارش رویدادها','queue'=>'صف پردازش','cache'=>'حافظه موقت','backup'=>'پشتیبان‌گیری','restore'=>'بازیابی','maintenance'=>'حالت تعمیرات','features'=>'ویژگی‌های آزمایشی'],
        'developer'=>['overview'=>'مرکز توسعه‌دهنده','docs'=>'مستندات API','apps'=>'برنامه‌ها','oauth'=>'OAuth','webhooks'=>'وب‌هوک‌ها','sandbox'=>'محیط آزمایشی'],
        'newsletter'=>['overview'=>'مرکز خبرنامه','subscribers'=>'مشترکان ایمیل','campaigns'=>'کمپین‌ها','offers'=>'پیشنهادهای هوشمند'],
        'sms'=>['overview'=>'مرکز پیامک','otp'=>'گزارش رمز یکبارمصرف','sent'=>'گزارش ارسال‌ها','settings'=>'تنظیمات پیامک'],
    ];

    public function index(Request $request, string $module, string $section='overview')
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        $sections=self::SECTIONS[$module] ?? ['overview'=>'مرکز '.self::MODULES[$module]];
        abort_unless(isset($sections[$section]), 404);

        $table=self::TABLES[$module] ?? null;
        $count=0;
        $rows=collect();
        if($table && Schema::hasTable($table)){
            $count=(int)DB::table($table)->count();
            $columns=Schema::getColumnListing($table);
            $query=DB::table($table);
            $search=trim((string)$request->input('q',''));
            if($search!=='' && in_array('name',$columns,true)) $query->where('name','like','%'.$search.'%');
            if($search!=='' && in_array('title',$columns,true)) $query->orWhere('title','like','%'.$search.'%');
            $select=array_values(array_filter(['id','name','title','status','is_active','created_at','updated_at'],fn($c)=>in_array($c,$columns,true)));
            if($select) $rows=$query->select($select)->latest(in_array('id',$columns,true)?'id':$select[0])->limit(12)->get();
        }

        $quickRoutes=[
            'users'=>['همه کاربران'=>'admin.users.index','نقش‌ها'=>'admin.roles.index'],
            'products'=>['همه محصولات'=>'admin.products.index','افزودن محصول'=>'admin.products.create','تأیید محصولات'=>'admin.products.approvals','دسته‌بندی‌ها'=>'admin.categories.index'],
            'orders'=>['همه سفارش‌ها'=>'admin.orders.index'],
            'finance'=>['کیف پول'=>'admin.wallets.index'],
            'content'=>['وبلاگ'=>'admin.blog.index','صفحات'=>'admin.content.index'],
            'marketing'=>['تخفیف‌ها'=>'admin.discounts.index'],
            'ai'=>['مرکز AI'=>'admin.ai.dashboard','ارزیابی'=>'admin.ai.evaluation','تنظیمات'=>'admin.ai.settings'],
            'support'=>['پشتیبانی'=>'admin.support.index'],
            'integrations'=>['کلیدهای API'=>'admin.integrations.keys'],
        ][$module] ?? [];

        return view('admin.modules.index',[
            'module'=>$module,'moduleTitle'=>self::MODULES[$module],'sections'=>$sections,'section'=>$section,
            'sectionTitle'=>$sections[$section],'table'=>$table,'count'=>$count,'rows'=>$rows,'quickRoutes'=>$quickRoutes,'q'=>$request->input('q',''),
        ]);
    }
}
