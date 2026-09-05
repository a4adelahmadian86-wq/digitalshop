@extends('layouts.app')

@section('title', 'ثبت تیکت پشتیبانی')

@section('content')
<main class="container support-form-page">
    <div class="form-card">
        <span>پشتیبانی فایل‌مارکت</span>
        <h1>درخواست خود را ثبت کنید</h1>
        <p>برای سؤال درباره محصول، مشکل فنی، پرداخت یا سایر موارد درخواست خود را ثبت کنید. در صورت فعال بودن دستیار هوشمند، پاسخ اولیه نیز در دسترس است.</p>

        <form method="POST" action="{{ route('support.store') }}">
            @csrf

            @if($relatedType)
                <input type="hidden" name="related_type" value="{{ $relatedType }}">
                <input type="hidden" name="related_id" value="{{ $relatedId }}">
            @endif

            <div class="field">
                <label for="support-subject">موضوع</label>
                <input id="support-subject" name="subject" value="{{ $subject }}" required maxlength="180">
            </div>

            <div class="field">
                <label for="support-category">دسته</label>
                <select id="support-category" name="category" required>
                    <option value="general">سؤال عمومی</option>
                    <option value="product" @selected($relatedType === 'product')>محصول</option>
                    <option value="order">سفارش</option>
                    <option value="account">حساب کاربری</option>
                    <option value="technical">مشکل فنی</option>
                    <option value="payment">پرداخت</option>
                    <option value="refund">بازگشت وجه</option>
                    <option value="complaint">اعتراض</option>
                    <option value="other">سایر</option>
                </select>
            </div>

            <div class="field">
                <label for="support-body">شرح درخواست</label>
                <textarea id="support-body" name="body" rows="8" required maxlength="10000" placeholder="جزئیات درخواست خود را بنویسید..."></textarea>
            </div>

            <div class="form-actions">
                <button class="submit" type="submit">ثبت تیکت</button>
                <button class="assistant-submit" type="button" data-ai-open>گفتگو با دستیار هوشمند</button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('styles')
<style>
.support-form-page{max-width:760px;padding:30px 0 70px}.form-card{background:#fff;border:1px solid #eaecf0;border-radius:20px;padding:28px}.form-card>span{font-size:11px;color:#6941c6;font-weight:900}.form-card h1{margin:7px 0}.form-card>p{color:#667085;font-size:12px;line-height:2}.field{display:grid;gap:6px;margin-top:15px}.field label{font-size:11px;font-weight:800}.field input,.field select,.field textarea{width:100%;box-sizing:border-box;border:1px solid #d0d5dd;border-radius:11px;padding:11px;font:inherit;background:#fff}.form-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:16px}.submit,.assistant-submit{border:0;border-radius:12px;padding:12px 20px;font-weight:800;cursor:pointer;font:inherit}.submit{background:#111827;color:#fff}.assistant-submit{background:#eef2ff;color:#4338ca}
</style>
@endpush
