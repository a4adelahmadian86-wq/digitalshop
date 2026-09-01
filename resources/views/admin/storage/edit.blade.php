@extends('admin.layout')

@section('title', 'ویرایش Storage')

@section('content')

<div class="admin-page narrow">

    <div class="admin-page-head">

        <div>

            <div class="admin-eyebrow">
                Storage
            </div>

            <h1>
                ویرایش Provider
            </h1>

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
            action="{{ route('admin.storage.update', $storageProvider) }}"
            class="admin-form"
        >

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>
                    نام Provider
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $storageProvider->name) }}"
                    required
                >

            </div>

            <div class="discount-used-info">

                نوع:

                <strong>
                    {{ strtoupper($storageProvider->type) }}
                </strong>

                <br>

                Disk:

                <code>
                    {{ $storageProvider->config['disk'] ?? 'local' }}
                </code>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('admin.storage.index') }}"
                    class="admin-secondary-btn"
                >
                    بازگشت
                </a>

                <button
                    type="submit"
                    class="admin-primary-btn"
                >
                    ذخیره
                </button>

            </div>

        </form>

    </div>

</div>

@endsection