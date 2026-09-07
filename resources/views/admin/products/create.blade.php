@extends('admin.layout')

@section('title', 'افزودن محصول')

@section('content')
<div class="admin-page">
    <div class="admin-page-head">
        <div>
            <div class="admin-eyebrow">مدیریت فروشگاه</div>
            <h1>افزودن محصول</h1>
            <p>ثبت محصول جدید و توضیحات حرفه‌ای آن</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="admin-secondary-btn">بازگشت به محصولات</a>
    </div>

    @if($errors->any())
        <div class="admin-alert error"><ul style="margin:0;padding-right:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <section class="admin-form-card">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-form">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="title">عنوان محصول</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input id="slug" type="text" name="slug" value="{{ old('slug') }}" required dir="ltr" autocomplete="off">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">دسته‌بندی</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">انتخاب دسته‌بندی</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="storage_provider_id">Storage Provider</label>
                    <select id="storage_provider_id" name="storage_provider_id" required>
                        <option value="">انتخاب محل ذخیره</option>
                        @foreach($storageProviders as $provider)
                            <option value="{{ $provider->id }}" @selected(old('storage_provider_id') == $provider->id)>{{ $provider->name }} — {{ strtoupper($provider->type) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="price">قیمت اصلی <span class="muted">(تومان)</span></label>
                <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" required inputmode="numeric">
            </div>

            <div class="form-group">
                <label for="short_description">توضیح کوتاه</label>
                <textarea id="short_description" name="short_description" rows="3" maxlength="1000">{{ old('short_description') }}</textarea>
            </div>

            <div class="form-group">
                <label>توضیحات کامل</label>
                <x-admin.rich-editor name="description" :value="old('description')" mode="product" height="460" placeholder="توضیحات محصول را بنویسید…" />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="thumbnail">تصویر محصول</label>
                    <input id="thumbnail" type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp">
                    <small>JPG، PNG یا WebP — حداکثر ۵ مگابایت</small>
                </div>
                <div class="form-group">
                    <label for="product_file">فایل اصلی محصول</label>
                    <input id="product_file" type="file" name="product_file" required>
                    <small>فایل قابل فروش محصول را انتخاب کنید.</small>
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" @checked(old('is_published'))> محصول منتشر شود</label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="admin-secondary-btn">انصراف</a>
                <button type="submit" class="admin-primary-btn">ذخیره و ایجاد محصول</button>
            </div>
        </form>
    </section>
</div>
@endsection
