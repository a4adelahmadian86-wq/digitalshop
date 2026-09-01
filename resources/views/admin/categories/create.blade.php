@extends('admin.layout')

@section('title', 'افزودن دسته‌بندی')

@section('content')

<div class="admin-page narrow">

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                مدیریت دسته‌بندی‌ها
            </div>

            <h1>
                افزودن دسته‌بندی
            </h1>

            <p>
                یک دسته‌بندی جدید برای محصولات ایجاد کنید.
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
            action="{{ route('admin.categories.store') }}"
            class="admin-form"
        >

            @csrf


            <div class="form-group">

                <label for="name">
                    نام دسته‌بندی
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="مثلاً آموزش برنامه‌نویسی"
                    required
                >

            </div>


            <div class="form-group">

                <label for="slug">
                    Slug
                </label>

                <input
                    id="slug"
                    type="text"
                    name="slug"
                    value="{{ old('slug') }}"
                    placeholder="programming"
                    dir="ltr"
                    required
                >

                <small>
                    فقط حروف انگلیسی، اعداد، خط تیره و زیرخط.
                </small>

            </div>


            <div class="form-group">

                <label for="description">
                    توضیحات
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    style="width:100%;box-sizing:border-box;border:1px solid #dfe3ea;border-radius:10px;padding:12px 13px;font-family:inherit;resize:vertical;"
                >{{ old('description') }}</textarea>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label for="image">
                        تصویر
                    </label>

                    <input
                        id="image"
                        type="text"
                        name="image"
                        value="{{ old('image') }}"
                        placeholder="مسیر تصویر"
                        dir="ltr"
                    >

                    <small>
                        در مرحله Media Management این بخش به آپلود واقعی متصل می‌شود.
                    </small>

                </div>


                <div class="form-group">

                    <label for="sort_order">
                        ترتیب نمایش
                    </label>

                    <input
                        id="sort_order"
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', 0) }}"
                        min="0"
                    >

                </div>

            </div>


            <div class="form-group">

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                    >

                    دسته‌بندی فعال باشد

                </label>

            </div>


            <div class="form-actions">

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="admin-secondary-btn"
                >
                    انصراف
                </a>

                <button
                    type="submit"
                    class="admin-primary-btn"
                >
                    ایجاد دسته‌بندی
                </button>

            </div>

        </form>

    </section>

</div>

@endsection