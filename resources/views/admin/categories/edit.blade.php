@extends('admin.layout')

@section('title', 'ویرایش دسته‌بندی')

@section('content')

<div class="admin-page narrow">

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                مدیریت دسته‌بندی‌ها
            </div>

            <h1>
                ویرایش دسته‌بندی
            </h1>

            <p>
                اطلاعات دسته‌بندی را ویرایش کنید.
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
            action="{{ route('admin.categories.update', $category) }}"
            class="admin-form"
        >

            @csrf
            @method('PUT')


            <div class="form-group">

                <label for="name">
                    نام دسته‌بندی
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
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
                    value="{{ old('slug', $category->slug) }}"
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
                >{{ old('description', $category->description) }}</textarea>

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
                        value="{{ old('image', $category->image) }}"
                        placeholder="مسیر تصویر"
                        dir="ltr"
                    >

                    <small>
                        مدیریت واقعی تصاویر را در مرحله Media/Storage وصل می‌کنیم.
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
                        value="{{ old('sort_order', $category->sort_order) }}"
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
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                    >

                    دسته‌بندی فعال باشد

                </label>

            </div>


            @if($category->products()->exists())

                <div class="discount-used-info">

                    این دسته‌بندی دارای
                    <strong>
                        {{ number_format($category->products()->count()) }}
                    </strong>
                    محصول است و قابل حذف نیست.

                </div>

            @endif


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
                    ذخیره تغییرات
                </button>

            </div>

        </form>

    </section>

</div>

@endsectionس