@extends('account.layout')
@section('title',$title)
@section('content')
<div class="admin-module-page" dir="rtl">
  <div class="admin-module-head">
    <div><span class="admin-kicker">مدیریت سامانه</span><h1>{{ $title }}@if($sub)<span> / {{ $subTitle }}</span>@endif</h1><p>{{ $subTitle }}</p></div>
    <a class="admin-back-link" href="{{ route('account.dashboard') }}"><x-icon name="arrow-right" size="16"/> بازگشت به پیشخوان</a>
  </div>
  <div class="admin-module-stats">@foreach($stats as $stat)<div class="admin-module-stat"><span class="admin-module-stat-icon"><x-icon name="{{ $stat['icon'] }}" size="20"/></span><div><small>{{ $stat['label'] }}</small><strong>{{ number_format($stat['value']) }}</strong></div></div>@endforeach</div>
  <div class="admin-module-grid">
    <section class="admin-module-card admin-module-primary">
      <div class="module-card-head"><div><span class="admin-kicker">مرکز مدیریت</span><h2>{{ $sub ? $subTitle : $title }}</h2></div><span class="module-status"><i></i> آماده</span></div>
      @if($module==='newsletter')
        <div class="module-feature"><x-icon name="send" size="28"/><div><strong>خبرنامه هوشمند محصولات</strong><p>کاربرانی که ایمیل ثبت کرده‌اند می‌توانند خبر محصولات جدید و پیشنهادهای مرتبط با فعالیت خود را دریافت کنند. انتخاب محصولات و زمان‌بندی ارسال در مرحله بعد به موتور پیشنهاد هوشمند متصل می‌شود.</p></div></div>
        <div class="module-actions"><a href="#" class="dj-btn primary">مدیریت مشترکان</a><a href="#" class="dj-btn">ساخت کمپین جدید</a></div>
      @elseif($module==='sms')
        <div class="module-feature"><x-icon name="send" size="28"/><div><strong>مرکز پیامک و رمز یکبارمصرف</strong><p>آمار ارسال رمز یکبارمصرف، وضعیت موفقیت، خطا، انقضا و محدودیت‌های روزانه در این بخش متمرکز می‌شود.</p></div></div>
        <div class="module-actions"><a href="#" class="dj-btn primary">گزارش ارسال‌ها</a><a href="#" class="dj-btn">تنظیمات سرویس پیامک</a></div>
      @elseif($module==='ai')
        <div class="module-feature"><x-icon name="ai" size="28"/><div><strong>مرکز هوش مصنوعی</strong><p>مدل‌ها با اولویت Gemini 2.5 Flash و Flash-Lite، مصرف، ارزیابی، سلامت سرویس و سهمیه‌ها از یک نقطه کنترل می‌شوند.</p></div></div>
        <div class="module-actions"><a href="{{ route('admin.ai.evaluation') }}" class="dj-btn primary">ارزیابی مدل‌ها</a><a href="{{ route('admin.ai.dashboard') }}" class="dj-btn">داشبورد هوش مصنوعی</a></div>
      @else
        <div class="module-feature"><x-icon name="dashboard" size="28"/><div><strong>این بخش آماده توسعه است</strong><p>ساختار صفحه، ناوبری، کارت‌ها، حالت فعال و طراحی یکپارچه ایجاد شده و داده‌ها و عملیات تخصصی این بخش می‌توانند بدون تغییر ظاهر به آن متصل شوند.</p></div></div>
        <div class="module-actions"><a href="{{ route('account.dashboard') }}" class="dj-btn primary">مشاهده پیشخوان</a><a href="{{ route('home') }}" class="dj-btn">مشاهده فروشگاه</a></div>
      @endif
    </section>
    <aside class="admin-module-card"><div class="module-card-head"><div><span class="admin-kicker">دسترسی سریع</span><h2>بخش‌های مرتبط</h2></div></div>
      <div class="module-links">
        @php($links=[['users','کاربران','users'],['products','محصولات','bag'],['orders','سفارش‌ها','document'],['finance','مالی','wallet'],['support','پشتیبانی','support'],['reports','گزارش‌ها و تحلیل‌ها','chart'],['storage','فضای ذخیره‌سازی','database'],['security','امنیت','lock']])
        @foreach($links as $link)<a href="{{ url('/admin/'.$link[0]) }}" class="{{ $module===$link[0]?'active':'' }}"><x-icon name="{{ $link[2] }}" size="17"/><span>{{ $link[1] }}</span><x-icon name="arrow-right" size="14"/></a>@endforeach
      </div>
    </aside>
  </div>
</div>
@endsection
@push('styles')
<style>.admin-module-page{max-width:1600px;margin:auto}.admin-module-head{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:24px}.admin-kicker{display:block;font-size:12px;color:#8a94a6;font-weight:800;margin-bottom:6px}.admin-module-head h1{margin:0;font-size:28px;letter-spacing:-.5px}.admin-module-head h1 span{font-size:17px;color:#7b8495;font-weight:600}.admin-module-head p{margin:7px 0 0;color:#7b8495}.admin-back-link{display:inline-flex;align-items:center;gap:7px;background:#fff;border:1px solid #e6e9f0;border-radius:12px;padding:11px 15px;color:#344054;font-weight:800}.admin-module-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}.admin-module-stat{display:flex;align-items:center;gap:13px;background:#fff;border:1px solid #e8ebf1;border-radius:17px;padding:17px;box-shadow:0 5px 20px rgba(16,24,40,.035)}.admin-module-stat-icon{width:44px;height:44px;display:grid;place-items:center;border-radius:13px;background:#eef1ff;color:#304aca}.admin-module-stat small,.admin-module-stat strong{display:block}.admin-module-stat small{color:#8a94a6;font-weight:700}.admin-module-stat strong{font-size:22px;margin-top:2px}.admin-module-grid{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(280px,.8fr);gap:18px}.admin-module-card{background:#fff;border:1px solid #e8ebf1;border-radius:20px;padding:22px;box-shadow:0 7px 28px rgba(16,24,40,.035)}.module-card-head{display:flex;justify-content:space-between;align-items:center;gap:15px}.module-card-head h2{margin:0;font-size:19px}.module-status{display:flex;align-items:center;gap:6px;font-size:11px;color:#17834b;font-weight:800;background:#eefaf3;padding:7px 10px;border-radius:99px}.module-status i{width:7px;height:7px;border-radius:50%;background:#21a366}.module-feature{margin-top:24px;background:#f7f8fc;border-radius:17px;padding:22px;display:flex;gap:15px;align-items:flex-start}.module-feature>svg,.module-feature .ds-icon-dual{color:#304aca;flex:none}.module-feature strong{font-size:16px}.module-feature p{color:#667085;line-height:2;margin:7px 0 0}.module-actions{display:flex;gap:10px;margin-top:18px;flex-wrap:wrap}.dj-btn{display:inline-flex;align-items:center;justify-content:center;border:1px solid #e2e6ef;background:#fff;color:#344054;border-radius:11px;padding:10px 15px;font-weight:800}.dj-btn.primary{background:#304aca;border-color:#304aca;color:#fff}.module-links{display:grid;gap:6px;margin-top:15px}.module-links a{display:flex;align-items:center;gap:9px;padding:12px;border-radius:11px;color:#475467;font-weight:800}.module-links a span{flex:1}.module-links a:hover,.module-links a.active{background:#f0f2ff;color:#304aca}@media(max-width:900px){.admin-module-head{align-items:flex-start;flex-direction:column}.admin-module-stats{grid-template-columns:repeat(2,1fr)}.admin-module-grid{grid-template-columns:1fr}}@media(max-width:560px){.admin-module-stats{grid-template-columns:1fr}.admin-module-card{padding:17px}.admin-module-head h1{font-size:23px}}</style>
@endpush
