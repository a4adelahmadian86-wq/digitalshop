@extends('admin.layout')

@section('title', 'افزودن Storage Provider')

@section('content')

<div class="admin-page narrow">

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                Storage
            </div>

            <h1>
                افزودن Provider
            </h1>

            <p>
                در این مرحله Local Storage فعال است.
            </p>

        </div>

    </div>

    @if($errors->any())

        <div class="admin-alert error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif

    <div class="admin-form-card">

        <form
            method="POST"
            action="{{ route('admin.storage.store') }}"
            class="admin-form"
        >

            @csrf

            <div class="form-group">

                <label>
                    نام Provider
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Local Storage"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    نوع Provider
                </label>

                <select name="type">

                    <option value="local">
                        Local Storage
                    </option>

                </select>

            </div>

            <div class="discount-used-info">

                Local Provider فایل‌ها را در:

                <br>

                <code>
                    storage/app/private
                </code>

                <br>

                ذخیره می‌کند.

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('admin.storage.index') }}"
                    class="admin-secondary-btn"
                >
                    انصراف
                </a>

                <button
                    type="submit"
                    class="admin-primary-btn"
                >
                    ذخیره Provider
                </button>

            </div>

        </form>

    </div>

</div>

@endsection