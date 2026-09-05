@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->title).' | فروشگاه فایل')
@section('description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->short_description ?: $product->description), 155))
@section('keywords', $product->seo_keywords ?: '')

@section('content')
<div class="container product-page-v2">
    <section class="product-hero">
        <a class="product-preview-card" href="{{ route('product.preview', $product) }}" aria-label="مشاهده پیش‌نمایش {{ $product->title }}">
            <div class="preview-paper">
                <div class="preview-paper-top"><span>پیش‌نمایش فایل</span><x-icon name="file" size="18"/></div>
                <div class="preview-paper-title">{{ $product->title }}</div>
                @if($previewExcerpt)
                    <div class="preview-paper-text">{{ $previewExcerpt }}</div>
                @else
                    <div class="preview-paper-lines"><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="preview-paper-note">برای مشاهده محتوای واقعی فایل، پیش‌نمایش را باز کنید.</div>
                @endif
                <div class="preview-paper-footer"><span>{{ $pdfPreview ? 'پیش‌نمایش آماده است' : 'نمونه محتوا' }}</span><strong>باز کردن پیش‌نمایش ←</strong></div>
            </div>
        </a>

        <div class="product-info">
            <div class="muted">{{ $product->category?->name ?: 'محصول دیجیتال' }}</div>
            <h1>{{ $product->title }}</h1>
            @if($product->short_description)<p>{{ $product->short_description }}</p>@endif
            <div class="product-price-wrap" id="productPrice"><span>قیمت محصول</span><div class="product-price">{{ number_format($product->price) }} <small>تومان</small></div></div>
            <div class="product-actions">
                <a href="{{ route('product.preview', $product) }}" class="product-action product-preview"><x-icon name="eye" size="17"/> مشاهده پیش‌نمایش</a>
                @if($downloadItem)
                    <div class="purchased-badge"><x-icon name="check" size="15"/> این محصول را خریداری کرده‌اید</div>
                    <a href="{{ route('product.reader', $product) }}" class="product-action product-read">مطالعه کامل</a>
                    <a href="{{ route('download', $downloadItem) }}" class="product-action product-download"><x-icon name="download" size="17"/> دانلود فایل</a>
                @else
                    <form method="POST" action="{{ route('cart.add', $product) }}">@csrf<button class="product-action product-buy" type="submit"><x-icon name="cart" size="17"/> افزودن به سبد</button></form>
                @endif
                <button class="product-support" type="button" data-ai-open aria-label="باز کردن دستیار برای سؤال یا مشکل"><x-icon name="support" size="16"/> سؤال یا مشکلی دارید؟</button>
            </div>
        </div>
    </section>

    <section class="product-specs">
        <div><b>فرمت فایل اصلی</b><span>{{ $product->file_format ?: 'ثبت نشده' }}</span></div>
        <div><b>تعداد صفحات</b><span>{{ $product->page_count ? number_format($product->page_count) : 'اعلام نشده' }}</span></div>
        <div><b>تعداد فایل</b><span>{{ number_format($product->files->count()) }}</span></div>
        <div><b>شناسه محصول</b><span>{{ $product->id }}</span></div>
    </section>

    <section class="product-trust">
        <div><b>محتوای واقعی فایل</b><span>توضیحات و پیشنهادها بر پایه محتوای قابل بررسی</span></div>
        <div><b>{{ $product->files->count() }} فایل</b><span>فایل‌های محصول با اطلاعات واقعی ثبت شده‌اند</span></div>
        <div><b>پیش‌نمایش</b><span>قبل از خرید محتوای فایل را بررسی کنید</span></div>
    </section>

    <section class="product-description"><h2>توضیحات محصول</h2><div class="description-body">{!! $product->description ?: '<p>توضیحات تکمیلی این محصول هنوز ثبت نشده است.</p>' !!}</div></section>

    <section class="feedback-grid">
        <div class="feedback-card">
            <div class="block-head"><div><h2>تجربه خریداران</h2><small>نظرات کاربران تجربه شخصی هستند.</small></div><strong>{{ $product->reviews->count() }}</strong></div>
            @forelse($product->reviews as $review)<article class="review"><div class="review-top"><b>{{ $review->user?->first_name ?: 'خریدار' }}</b><span>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></div><p>{{ $review->body }}</p></article>@empty<p class="empty">هنوز نظری ثبت نشده است.</p>@endforelse
            @if($downloadItem)<form method="POST" action="{{ route('product.review.store', $product) }}" class="feedback-form">@csrf<h3>نظر شما</h3><select name="rating" required><option value="">امتیاز</option><option value="5">۵ — عالی</option><option value="4">۴ — خوب</option><option value="3">۳ — متوسط</option><option value="2">۲ — ضعیف</option><option value="1">۱ — بسیار ضعیف</option></select><textarea name="body" rows="4" maxlength="3000" placeholder="تجربه واقعی خود از فایل را بنویسید..." required></textarea><button class="product-action product-buy" type="submit">ثبت نظر</button></form>@endif
        </div>
        <div class="feedback-card">
            <div class="block-head"><div><h2>پرسش و پاسخ</h2><small>سؤال‌های واقعی کاربران درباره محصول</small></div><strong>{{ $product->questions->count() }}</strong></div>
            @forelse($product->questions as $question)<article class="question"><div class="review-top"><b>{{ $question->user?->first_name ?: 'کاربر' }}</b></div><p>{{ $question->body }}</p>@foreach($question->answers as $answer)<div class="answer"><b>پاسخ</b><p>{{ $answer->body }}</p></div>@endforeach</article>@empty<p class="empty">هنوز پرسشی ثبت نشده است.</p>@endforelse
            @auth<form method="POST" action="{{ route('product.question.store', $product) }}" class="feedback-form">@csrf<h3>سؤال خود را بپرسید</h3><textarea name="body" rows="4" maxlength="3000" placeholder="چه چیزی درباره این محصول می‌خواهید بدانید؟" required></textarea><button class="product-action product-buy" type="submit">ثبت سؤال</button></form>@else<p class="login-hint">برای ثبت سؤال ابتدا وارد حساب کاربری شوید.</p>@endauth
        </div>
    </section>

    @if($related->count())<section class="recommend-section"><div class="section-head"><h2>محصولات مرتبط</h2><span>بر اساس دسته‌بندی</span></div><div class="product-grid">@foreach($related as $p)<a href="{{ route('product.show', $p) }}" class="mini-product"><div class="mini-cover"><img src="{{ $p->thumbnail_url }}" alt="{{ $p->title }}" loading="lazy"></div><b>{{ $p->title }}</b><small>{{ number_format($p->price) }} تومان</small></a>@endforeach</div></section>@endif
    @if($recommended->count())<section class="recommend-section"><div class="section-head"><h2>پیشنهاد برای شما</h2><span>بر اساس فعالیت ثبت‌شده</span></div><div class="product-grid">@foreach($recommended as $p)<a href="{{ route('product.show', $p) }}" class="mini-product"><div class="mini-cover"><img src="{{ $p->thumbnail_url }}" alt="{{ $p->title }}" loading="lazy"></div><b>{{ $p->title }}</b><small>{{ number_format($p->price) }} تومان</small></a>@endforeach</div></section>@endif
</div>
@endsection

@push('styles')
<style>
.product-page-v2{max-width:1320px;padding:34px 16px 75px}.product-hero{display:grid;grid-template-columns:minmax(280px,410px) minmax(0,1fr);gap:40px;align-items:center}.product-preview-card{display:block;text-decoration:none;color:inherit;min-height:380px;border-radius:26px;background:#f4f6fb;border:1px solid #e4e8f0;padding:24px;box-shadow:0 18px 50px rgba(16,24,40,.08);transition:transform .25s,box-shadow .25s}.product-preview-card:hover{transform:translateY(-4px);box-shadow:0 24px 60px rgba(16,24,40,.12)}.preview-paper{height:100%;min-height:330px;background:#fff;border:1px solid #e3e7ef;border-radius:17px;padding:20px;box-sizing:border-box;display:flex;flex-direction:column}.preview-paper-top,.preview-paper-footer{display:flex;justify-content:space-between;align-items:center;gap:10px}.preview-paper-top{font-size:11px;color:#3157d5;font-weight:900;border-bottom:1px solid #eef1f5;padding-bottom:12px}.preview-paper-title{font-size:18px;font-weight:900;line-height:1.7;margin:20px 0 12px}.preview-paper-text{font-size:12px;line-height:2.1;color:#667085;display:-webkit-box;-webkit-line-clamp:8;-webkit-box-orient:vertical;overflow:hidden}.preview-paper-lines{display:grid;gap:10px;margin-top:12px}.preview-paper-lines i{height:8px;border-radius:8px;background:#edf0f5}.preview-paper-lines i:nth-child(3){width:82%}.preview-paper-lines i:nth-child(5){width:68%}.preview-paper-note{font-size:11px;color:#98a2b3;margin-top:16px}.preview-paper-footer{margin-top:auto;padding-top:15px;border-top:1px solid #eef1f5;font-size:10px;color:#98a2b3}.preview-paper-footer strong{color:#3157d5}.product-info{min-width:0}.product-info h1{font-size:34px;line-height:1.55;margin:10px 0}.product-info>p{color:#667085;line-height:2}.product-price-wrap{display:inline-flex;flex-direction:column;align-items:flex-start;margin:18px 0 22px;padding:12px 16px;border-radius:15px;background:#fff;border:1px solid #ece9ff;box-shadow:0 8px 25px rgba(101,65,216,.08)}.product-price-wrap>span{font-size:10px;color:#98a2b3}.product-price{font-size:24px;font-weight:900;color:#5935ce}.product-price small{font-size:10px}.product-actions{display:flex;flex-wrap:wrap;gap:8px;align-items:center}.product-actions form{margin:0}.product-action{display:inline-flex;justify-content:center;align-items:center;gap:7px;border:0;border-radius:12px;padding:12px 18px;text-decoration:none;cursor:pointer;font-weight:800;font:inherit}.product-buy{background:#111827;color:#fff}.product-preview{background:#eef2ff;color:#4338ca}.product-read{background:#111827;color:#fff}.product-download{background:#ecfdf3;color:#027a48}.purchased-badge{display:inline-flex;align-items:center;gap:5px;padding:8px 11px;background:#ecfdf3;color:#027a48;border-radius:10px;font-size:11px}.product-support{display:inline-flex;align-items:center;gap:6px;color:#6941c6;background:transparent;border:0;font:inherit;font-size:11px;font-weight:800;padding:10px 3px;cursor:pointer}.product-specs{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-top:25px}.product-specs div,.product-trust div,.product-description,.feedback-card,.recommend-section{background:#fff;border:1px solid #eaecf0;border-radius:18px}.product-specs div{padding:16px}.product-specs b,.product-specs span{display:block}.product-specs b{font-size:11px;color:#667085}.product-specs span{margin-top:6px;font-size:14px;font-weight:900;color:#344054}.product-trust{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-top:12px}.product-trust div{padding:16px}.product-trust b,.product-trust span{display:block}.product-trust b{font-size:12px}.product-trust span{margin-top:6px;color:#98a2b3;font-size:10px;line-height:1.8}.product-description,.feedback-card,.recommend-section{margin-top:22px;padding:25px}.product-description h2,.feedback-card h2,.recommend-section h2{margin:0 0 14px;font-size:19px}.description-body{line-height:2.2;color:#344054;font-size:13px}.description-body h2{font-size:20px;margin:22px 0 9px}.description-body h3{font-size:17px;margin:18px 0 8px}.description-body ul,.description-body ol{padding-right:26px}.description-body a{color:#304aca;text-decoration:underline}.feedback-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:22px}.block-head,.section-head,.review-top{display:flex;justify-content:space-between;align-items:center;gap:10px}.block-head small,.section-head span{display:block;color:#98a2b3;font-size:10px;margin-top:4px}.review,.question{padding:13px 0;border-top:1px solid #f0f2f5}.review p,.question p{line-height:1.9;font-size:12px}.review-top span{letter-spacing:2px}.answer{margin-top:9px;padding:10px 12px;background:#f8fafc;border-radius:10px}.answer p{margin:4px 0}.feedback-form{display:grid;gap:8px;border-top:1px solid #edf0f4;margin-top:15px;padding-top:15px}.feedback-form textarea,.feedback-form select{border:1px solid #d0d5dd;border-radius:10px;padding:10px;font:inherit}.empty,.login-hint{font-size:11px;color:#98a2b3}.product-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.mini-product{display:flex;flex-direction:column;gap:7px;padding:12px;border:1px solid #eaecf0;border-radius:13px;text-decoration:none;color:inherit;min-width:0}.mini-cover{height:120px;border-radius:10px;background:#f8fafc;overflow:hidden}.mini-cover img{width:100%;height:100%;object-fit:cover}.mini-product b{font-size:11px}.mini-product small{font-size:10px;color:#667085}@media(max-width:900px){.product-hero,.feedback-grid{grid-template-columns:1fr}.product-preview-card{min-height:280px}.preview-paper{min-height:230px}.product-info h1{font-size:27px}.product-grid{grid-template-columns:1fr 1fr}.product-specs{grid-template-columns:1fr 1fr}.product-trust{grid-template-columns:1fr}}@media(max-width:520px){.product-page-v2{padding-left:10px;padding-right:10px}.product-grid,.product-specs,.product-trust{grid-template-columns:1fr}.product-description,.feedback-card,.recommend-section{padding:18px}.product-actions .product-action{width:100%}.product-support{width:100%;justify-content:center}}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){const box=document.getElementById('productPrice');if(!box)return;if('IntersectionObserver'in window){new IntersectionObserver(function(entries,observer){if(entries[0].isIntersecting){box.classList.add('reveal');observer.disconnect();}},{threshold:.6}).observe(box);}});
</script>
@endpush
