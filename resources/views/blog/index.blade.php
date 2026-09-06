@extends('layouts.app')
@section('title','وبلاگ فایل‌مارکت | آموزش و راهنما')
@section('description','راهنماهای کاربردی، آموزش‌ها و نکات انتخاب و استفاده از فایل‌های دیجیتال.')
@section('content')
<main class="blog-shell">
    <header class="blog-hero">
        <span class="blog-kicker">دانش و راهنمای انتخاب فایل</span>
        <h1>وبلاگ فایل‌مارکت</h1>
        <p>آموزش‌ها و راهنماهای کاربردی برای یادگیری، انتخاب بهتر فایل‌ها و استفاده حرفه‌ای‌تر از منابع دیجیتال.</p>
    </header>

    @if($featured)
        <article class="blog-feature">
            <div class="blog-feature-visual"><span>مقاله منتخب</span></div>
            <div class="blog-feature-body">
                <div class="blog-meta">
                    @if($featured->published_at)<span>{{ $featured->published_at->format('Y/m/d') }}</span>@endif
                    <span>راهنما و آموزش</span>
                </div>
                <h2>{{ $featured->title }}</h2>
                <p>{{ $featured->excerpt }}</p>
                <a class="blog-read" href="{{ route('blog.show',$featured->slug) }}">مطالعه مقاله</a>
            </div>
        </article>
    @endif

    <div class="blog-section-title">
        <h2>آخرین مطالب</h2>
        <span>{{ $posts->total() }} مقاله</span>
    </div>

    @if($posts->count())
        <section class="blog-grid">
            @foreach($posts as $post)
                <article class="blog-card">
                    <div class="blog-card-visual"><span>راهنما و آموزش</span></div>
                    <div class="blog-card-body">
                        <div class="blog-meta">@if($post->published_at)<span>{{ $post->published_at->format('Y/m/d') }}</span>@endif</div>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show',$post->slug) }}">مطالعه مقاله ←</a>
                    </div>
                </article>
            @endforeach
        </section>
        {{ $posts->links() }}
    @else
        <div class="blog-empty">به‌زودی مقالات کاربردی جدید منتشر می‌شود.</div>
    @endif
</main>
@endsection
@push('styles')<link rel="stylesheet" href="{{ asset('css/blog-premium.css') }}">@endpush
