<footer class="site-footer">
    <div class="container footer-top">
        <div class="footer-brand">
            <a class="brand footer-brand-link" href="{{ route('home') }}">
                <span class="brand-mark">ف</span>
                <span><strong>فایل‌مارکت</strong><small>بازار فایل‌های دیجیتال</small></span>
            </a>
            <p>مرجع خرید و دانلود فایل‌های دیجیتال، آموزشی و کاربردی. هدف ما ساده‌کردن پیدا کردن و دریافت فایل مناسب شماست.</p>
        </div>
        <div class="footer-column">
            <h3>دسترسی سریع</h3>
            <a href="{{ route('home') }}">خانه</a>
            <a href="{{ route('products.index') }}">فروشگاه</a>
            <a href="{{ route('cart') }}">سبد خرید</a>
            @guest<a href="{{ route('login') }}">ورود به حساب</a>@endguest
        </div>
        <div class="footer-column">
            <h3>راهنما</h3>
            <a href="{{ route('products.index') }}">دسته‌بندی فایل‌ها</a>
            <a href="{{ route('products.index') }}">فایل‌های جدید</a>
            <a href="{{ route('products.index') }}">جستجوی محصولات</a>
            @auth<a href="{{ route('account.dashboard') }}">حساب کاربری</a>@endauth
        </div>
        <div class="footer-column footer-contact">
            <h3>با ما همراه باشید</h3>
            <p>فایل‌های جدید و پیشنهادهای کاربردی را از دست نده.</p>
            <div class="footer-socials"><span>◎</span><span>◉</span><span>✉</span></div>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>© {{ date('Y') }} فایل‌مارکت — تمامی حقوق محفوظ است.</span>
        <span>ساخته‌شده برای یک خرید ساده و سریع</span>
    </div>
</footer>