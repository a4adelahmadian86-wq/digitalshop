@php($path=request()->path())
<nav class="panel-nav"><div class="panel-nav-title">حساب کاربری</div><a class="{{ request()->routeIs('account.dashboard')?'active':'' }}" href="{{ route('account.dashboard') }}"><x-icon name="home" size="17"/>پیشخوان</a><a class="{{ request()->routeIs('account.profile','account.security')?'active':'' }}" href="{{ route('account.profile') }}"><x-icon name="user" size="17"/>پروفایل کاربری</a><a class="{{ request()->routeIs('account.orders*')?'active':'' }}" href="{{ route('account.orders') }}"><x-icon name="bag" size="17"/>سفارش‌ها</a><a class="{{ request()->routeIs('account.files')?'active':'' }}" href="{{ route('account.files') }}"><x-icon name="download" size="17"/>فایل‌های من</a><a class="{{ request()->routeIs('account.reader*')?'active':'' }}" href="{{ route('account.reader') }}"><x-icon name="eye" size="17"/>خوانشگر و اشتراک</a><a class="{{ request()->routeIs('account.club')?'active':'' }}" href="{{ route('account.club') }}"><x-icon name="gift" size="17"/>باشگاه مشتریان</a><a class="{{ request()->routeIs('account.wallet')?'active':'' }}" href="{{ route('account.wallet') }}"><x-icon name="wallet" size="17"/>کیف پول</a><a class="{{ request()->routeIs('account.notifications*')?'active':'' }}" href="{{ route('account.notifications') }}"><x-icon name="notification" size="17"/>اعلان‌ها @if($panelUnread)<b class="nav-badge">{{ $panelUnread }}</b>@endif</a><a class="{{ str_starts_with($path,'support')?'active':'' }}" href="{{ route('support.index') }}"><x-icon name="support" size="17"/>پشتیبانی و تیکت‌ها</a>
@if($user->hasRole('seller'))<div class="panel-nav-title">فروشنده</div><a href="{{ route('seller.products.index') }}"><x-icon name="bag" size="17"/>محصولات من</a><a href="{{ route('seller.products.create') }}"><x-icon name="add-file" size="17"/>افزودن محصول</a>@endif
@if($user->hasRole('admin'))<div class="panel-nav-title">مدیریت سامانه</div>@php
$groups=[
'users'=>['کاربران','users.manage',['همه کاربران','کاربران فعال','در انتظار تأیید','نقش‌ها و دسترسی‌ها','گروه‌های کاربری','فعالیت کاربران']],
'products'=>['محصولات','products.manage',['همه محصولات','افزودن محصول','پیش‌نویس‌ها','در انتظار تأیید','تأییدشده','ردشده','دسته‌بندی‌ها','ویژگی‌ها','قیمت‌گذاری','نظرات']],
'orders'=>['سفارش‌ها','orders.manage',['همه سفارش‌ها','در انتظار پرداخت','موفق','ناموفق','لغوشده','تکمیل‌شده','استرداد وجه']],
'finance'=>['مالی','finance.manage',['تراکنش‌ها','پرداخت‌ها','کیف پول','برداشت‌ها','تسویه‌ها','فاکتورها','کمیسیون‌ها','مالیات']],
'subscriptions'=>['اشتراک‌ها','subscriptions.manage',['اشتراک‌ها','پلن‌ها','تمدیدها','تغییر پلن']],
'assets'=>['فایل‌ها و دارایی‌ها','assets.manage',['کتابخانه فایل‌ها','پوشه‌ها','فایل‌های من','فایل‌های خریداری‌شده','فضای ذخیره‌سازی']],
'reader'=>['خوانشگر','reader.manage',['کتابخانه','بوکمارک‌ها','هایلایت‌ها','یادداشت‌ها','تاریخچه مطالعه']],
'loyalty'=>['باشگاه مشتریان','loyalty.manage',['مشتریان','امتیازات','سطوح','جوایز']],
'content'=>['محتوا','content.manage',['صفحات','وبلاگ','دسته‌بندی‌ها','رسانه','بنرها','نظرات']],
'marketing'=>['بازاریابی','marketing.manage',['تخفیف‌ها','کمپین‌ها','همکاری در فروش','معرفی دوستان','تبلیغات']],
'ai'=>['هوش مصنوعی','ai.manage',['مرکز هوش مصنوعی','مدل‌ها','ارائه‌دهندگان','ارزیابی مدل‌ها','پرامپت‌ها','تولید محتوا','مصرف و اعتبار','تنظیمات AI']],
'support'=>['پشتیبانی','support.manage',['مرکز پشتیبانی','تیکت‌ها','تیکت‌های من','ارجاع‌ها','زمان پاسخ و حل','پایگاه دانش']],
'notifications'=>['اعلان‌ها','notifications.manage',['اعلان‌ها','ارسال اعلان','قالب‌ها','کمپین‌های اطلاع‌رسانی']],
'reports'=>['گزارش‌ها و تحلیل‌ها','reports.manage',['گزارش فروش','گزارش مالی','گزارش کاربران','گزارش محصولات','گزارش محتوا','گزارش هوش مصنوعی','گزارش پشتیبانی']],
'search'=>['جستجو','search.manage',['تنظیمات جستجو','تحلیل جستجو','پیشنهادها']],
'sellers'=>['فروشندگان','sellers.manage',['مدیریت فروشندگان','درخواست‌های فروشندگی','عملکرد','کمیسیون']],
'workflow'=>['گردش کار','admin.modules',['گردش کار','تأییدها','صف‌های کاری']],
'tasks'=>['وظایف','admin.modules',['وظایف من','وظایف تیم','یادآوری‌ها']],
'security'=>['امنیت','security.manage',['مرکز امنیت','ورودها','نشست‌ها','رخدادهای امنیتی','گزارش حسابرسی']],
'integrations'=>['یکپارچه‌سازی‌ها','admin.modules',['سرویس‌ها','API','کلیدهای API','وب‌هوک‌ها','سرویس‌های خارجی']],
'settings'=>['تنظیمات','settings.manage',['عمومی','فروشگاه','پرداخت','ایمیل','پیامک','اعلان‌ها','امنیت']],
'system'=>['سیستم','system.manage',['سلامت سیستم','گزارش رویدادها','صف پردازش','حافظه موقت','پشتیبان‌گیری','بازیابی','حالت تعمیرات','ویژگی‌های آزمایشی']],
'developer'=>['توسعه‌دهنده','admin.modules',['مستندات API','برنامه‌ها','OAuth','وب‌هوک‌ها','محیط آزمایشی']],
'newsletter'=>['خبرنامه محصولات','newsletter.manage',['مرکز خبرنامه','مشترکان ایمیل','کمپین‌ها','پیشنهادهای هوشمند']],
'sms'=>['پیامک و رمز یکبارمصرف','sms.manage',['مرکز پیامک','گزارش رمز یکبارمصرف','گزارش ارسال‌ها','تنظیمات پیامک']],
];
$icons=['users'=>'users','products'=>'bag','orders'=>'document','finance'=>'wallet','subscriptions'=>'clock','assets'=>'folder','reader'=>'eye','loyalty'=>'gift','content'=>'document','marketing'=>'discount','ai'=>'ai','support'=>'support','notifications'=>'notification','reports'=>'chart','search'=>'search','sellers'=>'users','workflow'=>'check','tasks'=>'calendar','security'=>'lock','integrations'=>'code','settings'=>'settings','system'=>'dashboard','developer'=>'code','newsletter'=>'send','sms'=>'message'];
$special=['users'=>['نقش‌ها و دسترسی‌ها'=>url('/admin/roles')],'products'=>['افزودن محصول'=>route('admin.products.create'),'در انتظار تأیید'=>route('admin.products.approvals')],'content'=>['صفحات'=>route('admin.content.index'),'وبلاگ'=>route('admin.blog.index')],'marketing'=>['تخفیف‌ها'=>route('admin.discounts.index')],'ai'=>['مرکز هوش مصنوعی'=>route('admin.ai.dashboard'),'ارزیابی مدل‌ها'=>route('admin.ai.evaluation'),'تنظیمات AI'=>route('admin.ai.settings')],'support'=>['مرکز پشتیبانی'=>route('admin.support.index')],'settings'=>['عمومی'=>route('admin.settings.general')]];
@endphp
@foreach($groups as $key=>$group)@if($user->hasPermission($group[1]))<div class="panel-nav-group {{ str_starts_with($path,'admin/'.$key)?'open':'' }}"><button type="button" class="panel-nav-parent" aria-expanded="{{ str_starts_with($path,'admin/'.$key)?'true':'false' }}"><x-icon name="{{ $icons[$key]??'folder' }}" size="17"/><span>{{ $group[0] }}</span><x-icon name="arrow-left" size="14"/></button><div class="panel-submenu">@foreach($group[2] as $i=>$sub)@php($href=$special[$key][$sub]??url('/admin/'.$key.'/'.($i===0?'overview':$i)))<a class="{{ request()->fullUrlIs($href)?'active':'' }}" href="{{ $href }}">{{ $sub }}</a>@endforeach</div></div>@endif @endforeach@endif
</nav>