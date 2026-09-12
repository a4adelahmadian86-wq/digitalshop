<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function terms()
    {
        return view('pages.static', [
            'title' => 'قوانین و مقررات',
            'body' => 'قوانین استفاده از فروشگاه فایل‌مارکت. این متن از پنل مدیریت قابل تکمیل است.',
        ]);
    }

    public function faq()
    {
        return view('pages.static', [
            'title' => 'پرسش‌های متداول',
            'body' => 'پاسخ پرسش‌های رایج کاربران درباره خرید، دانلود و کیف پول در این صفحه قرار می‌گیرد.',
        ]);
    }

    public function contact()
    {
        return view('pages.static', [
            'title' => 'تماس با ما',
            'body' => 'از طریق پشتیبانی داخل حساب کاربری یا ایمیل سایت با ما در ارتباط باشید.',
        ]);
    }

    public function support()
    {
        return view('pages.static', [
            'title' => 'پشتیبانی',
            'body' => 'برای پیگیری سفارش و مشکلات دانلود از بخش پشتیبانی حساب کاربری استفاده کنید.',
        ]);
    }

    public function blog()
    {
        return view('pages.static', [
            'title' => 'وبلاگ',
            'body' => 'مقالات آموزشی و معرفی فایل‌ها به‌زودی در این بخش منتشر می‌شوند.',
        ]);
    }
}
