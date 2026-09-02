<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id(); $table->string('slug')->unique(); $table->string('title'); $table->string('meta_title')->nullable(); $table->text('meta_description')->nullable(); $table->longText('content')->nullable(); $table->boolean('is_published')->default(true); $table->timestamps();
        });
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id(); $table->string('slug')->unique(); $table->string('title'); $table->text('excerpt')->nullable(); $table->longText('content'); $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete(); $table->boolean('is_published')->default(false); $table->timestamp('published_at')->nullable(); $table->timestamps();
        });
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('subject'); $table->string('category')->default('general'); $table->string('status')->default('open'); $table->string('priority')->default('normal'); $table->string('related_type')->nullable(); $table->unsignedBigInteger('related_id')->nullable(); $table->boolean('ai_handled')->default(false); $table->boolean('human_requested')->default(false); $table->timestamp('human_requested_at')->nullable(); $table->timestamps();
            $table->index(['related_type','related_id']);
        });
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id(); $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->string('sender_type')->default('user'); $table->longText('body'); $table->boolean('is_ai')->default(false); $table->timestamps();
        });
        DB::table('site_pages')->insert([
            ['slug'=>'terms','title'=>'قوانین خرید و استفاده','meta_title'=>'قوانین خرید و استفاده | فایل‌مارکت','meta_description'=>'قوانین خرید، استفاده، حقوق محتوا، حریم خصوصی و شرایط خدمات فایل‌مارکت.','content'=>'<h2>مقدمه</h2><p>استفاده از فایل‌مارکت به معنی پذیرش قوانین جاری سایت است. هدف این قوانین ایجاد تعادل میان حقوق خریداران، فروشندگان و خود سامانه است.</p><h2>خرید و تحویل</h2><p>پس از پرداخت موفق، دسترسی به محصول خریداری‌شده مطابق وضعیت محصول و سفارش در حساب کاربری فعال می‌شود. کاربر باید پیش از خرید توضیحات و پیش‌نمایش را بررسی کند.</p><h2>حقوق مالکیت فکری</h2><p>فروشنده مسئول قانونی بودن و داشتن حق عرضه محتوای بارگذاری‌شده است. انتشار محتوای ناقض حقوق مؤلف، مالکیت فکری، حریم خصوصی یا قوانین کشور ممنوع است.</p><h2>بازگشت وجه</h2><p>درخواست بازگشت وجه از مسیر پشتیبانی بررسی می‌شود. برای محتوای دیجیتال، وضعیت دسترسی و استفاده از فایل، نوع مشکل و مقررات لازم در تصمیم‌گیری مؤثر است. هیچ شرط قراردادی نباید حقوق قانونی مصرف‌کننده را محدود کند.</p><h2>رفتار کاربران</h2><p>ایجاد حساب جعلی، سوءاستفاده از تخفیف، انتشار لینک دانلود، تلاش برای دور زدن محدودیت‌های امنیتی و هرگونه استفاده مخرب ممنوع است.</p><h2>مسئولیت فروشنده</h2><p>فروشنده باید مشخصات، فرمت، کیفیت و محتوای محصول را صادقانه معرفی کند. فایل باید با توضیحات و عنوان آن مطابقت داشته باشد.</p><h2>حریم خصوصی</h2><p>اطلاعات کاربران صرفاً برای ارائه خدمات، امنیت، پرداخت، پشتیبانی و بهبود تجربه استفاده می‌شود و نگهداری و پردازش آن تابع قوانین و سیاست حریم خصوصی سایت است.</p><h2>تغییر قوانین</h2><p>ممکن است برای بهبود خدمات یا انطباق با مقررات، متن قوانین تغییر کند. نسخه جاری در همین صفحه منتشر می‌شود.</p><p><strong>توجه:</strong> این متن یک چارچوب عمومی کسب‌وکار است و جایگزین مشاوره حقوقی اختصاصی نیست.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['slug'=>'faq','title'=>'سوالات متداول','meta_title'=>'سوالات متداول | فایل‌مارکت','meta_description'=>'پاسخ سوالات متداول درباره خرید، دانلود، حساب کاربری و پشتیبانی.','content'=>'<div class="faq-item"><button type="button">چطور یک فایل را خریداری کنم؟</button><div><p>محصول را انتخاب کنید، پیش‌نمایش را بررسی کنید، آن را به سبد اضافه کرده و پرداخت را تکمیل کنید.</p></div></div><div class="faq-item"><button type="button">آیا قبل از خرید می‌توانم فایل را ببینم؟</button><div><p>برای محصولاتی که پیش‌نمایش دارند، بخشی از محتوای واقعی فایل در Reader نمایش داده می‌شود.</p></div></div><div class="faq-item"><button type="button">اگر فایل مشکل داشت چه کنم؟</button><div><p>از بخش پشتیبانی تیکت ثبت کنید و محصول یا سفارش مرتبط را انتخاب کنید تا بررسی سریع‌تر انجام شود.</p></div></div><div class="faq-item"><button type="button">آیا می‌توانم درخواست بازگشت وجه بدهم؟</button><div><p>بله، درخواست خود را از پشتیبانی ثبت کنید. درخواست‌های مالی و اعتراض‌ها توسط اپراتور انسانی بررسی می‌شوند.</p></div></div><div class="faq-item"><button type="button">پاسخ هوش مصنوعی قابل اعتماد است؟</button><div><p>پاسخ‌های عمومی ممکن است توسط AI داده شوند، اما موارد فنی حساس، اعتراض، اختلاف مالی و بازگشت وجه به انسان ارجاع می‌شوند.</p></div></div>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['slug'=>'contact','title'=>'تماس با ما','meta_title'=>'تماس با ما | فایل‌مارکت','meta_description'=>'ارتباط با تیم فایل‌مارکت و ثبت درخواست پشتیبانی.','content'=>'<p>برای شروع، سریع‌ترین راه ارتباط با فایل‌مارکت ثبت تیکت پشتیبانی است. در هر صفحه‌ای که نیاز به کمک دارید می‌توانید درخواست خود را ثبت کنید.</p><div class="contact-cards"><div><strong>پشتیبانی</strong><p>ثبت و پیگیری درخواست از طریق سامانه تیکتینگ.</p></div><div><strong>ارتباطات آینده</strong><p>ایمیل، ایتا و پیامک پس از راه‌اندازی در همین صفحه اضافه خواهند شد.</p></div></div>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
    public function down(): void { Schema::dropIfExists('support_messages'); Schema::dropIfExists('support_tickets'); Schema::dropIfExists('blog_posts'); Schema::dropIfExists('site_pages'); }
};
