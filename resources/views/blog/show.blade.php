@extends('layouts.app')
@section('title',$post->title.' | وبلاگ فایل‌مارکت')
@section('description',$post->excerpt)
@section('content')
<main class="blog-shell">
    <div class="article-layout">
        <article class="article-main article-card">
            <span class="article-kicker">وبلاگ فایل‌مارکت</span>
            <h1>{{ $post->title }}</h1>
            <div class="article-meta">
                <span>نویسنده: {{ $post->author?->name ?: 'تحریریه فایل‌مارکت' }}</span>
                @if($post->published_at)<span>{{ $post->published_at->format('Y/m/d') }}</span>@endif
                <span>{{ $readingMinutes }} دقیقه مطالعه</span>
            </div>
            @if($post->excerpt)<p class="article-lead">{{ $post->excerpt }}</p>@endif
            <div class="article-content">{!! $contentWithAnchors !!}</div>
            <div class="article-tools">
                <span style="font-size:11px;color:#8a93a3">اگر این مطلب برایتان مفید بود، آن را با دیگران به اشتراک بگذارید.</span>
                <button type="button" data-blog-copy>کپی لینک مقاله</button>
            </div>
        </article>

        <aside class="article-side">
            @if(count($toc))
                <nav class="toc" aria-label="فهرست مقاله">
                    <h2>در این مقاله می‌خوانید</h2>
                    @foreach($toc as $item)
                        <a class="{{ $item['level'] === 3 ? 'sub' : '' }}" href="#{{ $item['id'] }}">{{ $item['text'] }}</a>
                    @endforeach
                </nav>
            @endif

            @if($related->count())
                <section class="related-box">
                    <h2>مطالب مرتبط</h2>
                    @foreach($related as $item)
                        <a class="related-item" href="{{ route('blog.show',$item->slug) }}">
                            @if($item->published_at)<small>{{ $item->published_at->format('Y/m/d') }}</small>@endif
                            <strong>{{ $item->title }}</strong>
                        </a>
                    @endforeach
                </section>
            @endif
        </aside>
    </div>
</main>
@endsection
@push('styles')<link rel="stylesheet" href="{{ asset('css/blog-premium.css') }}">@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){const b=document.querySelector('[data-blog-copy]');if(!b)return;b.addEventListener('click',async function(){try{await navigator.clipboard.writeText(window.location.href);const t=b.textContent;b.textContent='لینک کپی شد';setTimeout(()=>b.textContent=t,1800)}catch(e){window.prompt('لینک مقاله را کپی کنید:',window.location.href)}})});
</script>
@endpush
