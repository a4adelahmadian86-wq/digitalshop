@extends('admin.layout')

@section('title', 'افزودن محصول')

@section('content')

<div class="admin-page">

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                مدیریت محصولات
            </div>

            <h1>
                افزودن محصول
            </h1>

            <p>
                مشخصات محصول، فایل و اطلاعات انتشار را ثبت کنید.
            </p>

        </div>

    </div>


    @if($errors->any())

        <div class="admin-alert error">

            <ul style="margin:0;padding-right:20px;">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <section class="admin-form-card">

        <form
            method="POST"
            action="{{ route('admin.products.store') }}"
            enctype="multipart/form-data"
            class="admin-form"
        >

            @csrf


            {{-- عنوان + دسته‌بندی --}}

            <div class="form-row">

                <div class="form-group">

                    <label for="title">
                        نام محصول
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="category_id">
                        دسته‌بندی
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        required
                        style="width:100%;box-sizing:border-box;border:1px solid #dfe3ea;border-radius:10px;padding:12px 13px;font-family:inherit;background:#fff;"
                    >

                        <option value="">
                            انتخاب دسته‌بندی
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- Slug + قیمت --}}

            <div class="form-row">

                <div class="form-group">

                    <label for="slug">
                        Slug
                    </label>

                    <input
                        id="slug"
                        type="text"
                        name="slug"
                        value="{{ old('slug') }}"
                        placeholder="مثلاً photoshop-course"
                        dir="ltr"
                    >

                    <small>
                        اگر خالی باشد، سیستم آن را تولید می‌کند.
                    </small>

                </div>


                <div class="form-group">

                    <label for="price">
                        قیمت
                    </label>

                    <input
                        id="price"
                        type="number"
                        name="price"
                        value="{{ old('price', 0) }}"
                        min="0"
                        required
                    >

                    <small>
                        مبلغ به تومان
                    </small>

                </div>

            </div>


            {{-- Storage Provider --}}

            <div class="form-group">

                <label for="storage_provider_id">
                    Storage Provider
                </label>

                <select
                    id="storage_provider_id"
                    name="storage_provider_id"
                    required
                    style="width:100%;box-sizing:border-box;border:1px solid #dfe3ea;border-radius:10px;padding:12px 13px;font-family:inherit;background:#fff;"
                >

                    <option value="">
                        انتخاب Storage
                    </option>

                    @foreach($storageProviders as $provider)

                        <option
                            value="{{ $provider->id }}"
                            @selected(
                                old(
                                    'storage_provider_id',
                                    $provider->is_default ? $provider->id : null
                                ) == $provider->id
                            )
                        >
                            {{ $provider->name }}
                            — {{ strtoupper($provider->type) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- توضیح کوتاه --}}

            <div class="form-group">

                <label for="short_description">
                    توضیح کوتاه
                </label>

                <textarea
                    id="short_description"
                    name="short_description"
                    rows="3"
                    style="width:100%;box-sizing:border-box;border:1px solid #dfe3ea;border-radius:10px;padding:12px 13px;font-family:inherit;resize:vertical;"
                >{{ old('short_description') }}</textarea>

            </div>


            {{-- توضیحات کامل --}}

            <div class="form-group">

                <label for="description">
                    توضیحات کامل محصول
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="10"
                    style="width:100%;box-sizing:border-box;border:1px solid #dfe3ea;border-radius:10px;padding:12px 13px;font-family:inherit;resize:vertical;"
                >{{ old('description') }}</textarea>

            </div>


            {{-- تصویر محصول --}}

            <div class="form-group">

                <label for="thumbnail">
                    تصویر محصول
                </label>

                <input
                    id="thumbnail"
                    type="file"
                    name="thumbnail"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small>
                    JPG، PNG یا WEBP — حداکثر 5MB
                </small>

            </div>


            {{-- فایل اصلی محصول --}}

            <div class="form-group">

                <label for="product_file">
                    فایل محصول
                </label>

                <input
                    id="product_file"
                    type="file"
                    name="product_file"
                    required
                >

                <small>
                    فایل اصلی محصول در Storage خصوصی ذخیره می‌شود.
                    حداکثر 500MB
                </small>

            </div>


            {{-- وضعیت انتشار --}}

            <div class="form-group">

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="is_published"
                        value="1"
                        @checked(old('is_published'))
                    >

                    محصول منتشر شود

                </label>

            </div>


            {{-- دکمه‌ها --}}

            <div class="form-actions">

                <a
                    href="{{ route('admin.products.index') }}"
                    class="admin-secondary-btn"
                >
                    انصراف
                </a>

                <button
                    type="submit"
                    class="admin-primary-btn"
                >
                    ایجاد محصول
                </button>

            </div>

        </form>

    </section>

</div>

@endsection