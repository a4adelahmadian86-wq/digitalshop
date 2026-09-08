@extends('admin.layout')

@section('title', 'ویرایش محصول')

@section('content')
<div class="admin-page narrow">
    <div class="admin-page-head">
        <div>
            <div class="admin-eyebrow">مدیریت محصولات</div>
            <h1>ویرایش محصول</h1>
            <p>{{ $product->title }}</p>
        </div>
    </div>

    @if(session('success'))<div class="admin-alert success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="admin-alert error">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="admin-alert error"><ul style="margin:0;padding-right:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <section class="admin-form-card">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group"><label for="title">عنوان محصول</label><input id="title" type="text" name="title" value="{{ old('title', $product->title) }}" required autocomplete="off"></div>
                <div class="form-group"><label for="slug">Slug</label><input id="slug" type="text" name="slug" value="{{ old('slug', $product->slug) }}" required dir="ltr" autocomplete="off"></div>
            </div>

            <div class="form-row">
                <div class="form-group"><label for="category_id">دسته‌بندی</label><select id="category_id" name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                <div class="form-group"><label for="storage_provider_id">Storage Provider</label><select id="storage_provider_id" name="storage_provider_id" required>@foreach($storageProviders as $provider)<option value="{{ $provider->id }}" @selected(old('storage_provider_id', $product->storage_provider_id) == $provider->id)>{{ $provider->name }} — {{ strtoupper($provider->type) }}</option>@endforeach</select></div>
            </div>

            <div class="form-group"><label for="price">قیمت اصلی <span class="muted">(تومان)</span></label><input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required inputmode="numeric"></div>
            <div class="form-group"><label for="short_description">توضیح کوتاه</label><textarea id="short_description" name="short_description" rows="3" maxlength="1000">{{ old('short_description', $product->short_description) }}</textarea></div>
            <div class="form-group"><label>توضیحات کامل</label><x-admin.rich-editor name="description" :value="old('description', $product->description)" mode="product" height="460" placeholder="توضیحات محصول را بنویسید…" /></div>

            <div class="form-group">
                <label for="thumbnail">تصویر محصول</label>
                @if($product->thumbnail)
                    <div style="margin-bottom:10px"><img src="{{ route('product.image', $product) }}" alt="{{ $product->title }}" loading="eager" decoding="async" style="width:180px;height:120px;border-radius:10px;object-fit:cover;display:block"></div>
                @endif
                <input id="thumbnail" type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp">
                <small>در صورت انتخاب نکردن، تصویر فعلی حفظ می‌شود.</small>
            </div>

            <div class="admin-box">
                <div class="admin-box-head"><div><span class="muted">فایل محصول</span><h2>Storage</h2></div></div>
                @if($product->storage_path || $product->file_path)<div class="discount-used-info"><strong>فایل فعلی:</strong> {{ $product->file_name ?? 'بدون نام' }}@if($product->storage_path)<br>مسیر Storage: <span dir="ltr">{{ $product->storage_path }}</span>@elseif($product->file_path)<br>مسیر فعلی: <span dir="ltr">{{ $product->file_path }}</span>@endif</div>@else<div class="admin-alert error">هنوز فایل اصلی محصول آپلود نشده است.</div>@endif
                <div class="form-group" style="margin-top:15px;margin-bottom:0"><label for="product_file">فایل جدید محصول</label><input id="product_file" type="file" name="product_file"><small>در صورت انتخاب نکردن، فایل فعلی حفظ می‌شود.</small></div>
            </div>

            <div class="form-group"><label class="checkbox-label"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $product->is_published))> محصول منتشر شود</label></div>
            <div class="form-actions"><a href="{{ route('admin.products.index') }}" class="admin-secondary-btn">انصراف</a><button type="submit" class="admin-primary-btn">ذخیره تغییرات</button></div>
        </form>
    </section>
</div>
@endsection
