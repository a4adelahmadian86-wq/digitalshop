@props([
    'name',
    'value' => '',
    'mode' => 'product',
    'placeholder' => 'متن خود را اینجا بنویسید…',
    'height' => 430,
])

@php
    $editorId = 'ds-rich-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', $name) . '-' . uniqid();
    $fullMode = $mode === 'full';
@endphp

<div
    class="ds-rich-editor"
    id="{{ $editorId }}"
    data-ds-editor
    data-mode="{{ $mode }}"
    data-name="{{ $name }}"
    data-autosave="1"
    style="--ds-editor-height: {{ (int) $height }}px"
>
    <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}" data-ds-editor-value>

    <div class="ds-editor-shell">
        <div class="ds-editor-toolbar" role="toolbar" aria-label="ابزارهای ویرایش متن">
            <div class="ds-tool-group">
                <button type="button" class="ds-tool ds-tool-select" data-command="format" data-value="p" title="سبک متن">
                    <span class="ds-tool-label">متن عادی</span><span class="ds-chevron">⌄</span>
                </button>
                <div class="ds-format-menu" hidden>
                    <button type="button" data-command="format" data-value="p">متن عادی</button>
                    @if($fullMode)<button type="button" data-command="format" data-value="h1">عنوان ۱</button>@endif
                    <button type="button" data-command="format" data-value="h2">عنوان ۲</button>
                    <button type="button" data-command="format" data-value="h3">عنوان ۳</button>
                    <button type="button" data-command="format" data-value="h4">عنوان ۴</button>
                </div>
            </div>

            <span class="ds-tool-separator"></span>

            <div class="ds-tool-group">
                <button type="button" class="ds-tool" data-command="bold" title="درشت (Ctrl+B)"><span class="ds-icon">{!! $icons['bold'] ?? '' !!}</span></button>
                <button type="button" class="ds-tool" data-command="italic" title="مورب (Ctrl+I)"><span class="ds-icon">{!! $icons['italic'] ?? '' !!}</span></button>
                <button type="button" class="ds-tool" data-command="underline" title="زیرخط (Ctrl+U)"><span class="ds-icon">{!! $icons['underline'] ?? '' !!}</span></button>
            </div>

            <span class="ds-tool-separator"></span>

            <div class="ds-tool-group">
                <div class="ds-color-wrap">
                    <button type="button" class="ds-tool ds-color-button" data-color-menu="text" title="رنگ متن"><span class="ds-icon ds-color-letter">A</span></button>
                    <div class="ds-color-menu" data-menu="text" hidden>
                        <button type="button" data-command="foreColor" data-value="#1f2937" style="--swatch:#1f2937" title="ذغالی"></button>
                        <button type="button" data-command="foreColor" data-value="#315c7c" style="--swatch:#315c7c" title="آبی برند"></button>
                        <button type="button" data-command="foreColor" data-value="#176b5b" style="--swatch:#176b5b" title="سبز آرام"></button>
                        <button type="button" data-command="foreColor" data-value="#8a5a00" style="--swatch:#8a5a00" title="طلایی تیره"></button>
                        <button type="button" data-command="foreColor" data-value="#9f3a38" style="--swatch:#9f3a38" title="قرمز کنترل‌شده"></button>
                        <button type="button" data-command="removeFormat" data-value="color" title="حذف رنگ">×</button>
                    </div>
                </div>
                <div class="ds-color-wrap">
                    <button type="button" class="ds-tool ds-color-button" data-color-menu="highlight" title="هایلایت متن"><span class="ds-icon ds-highlight-icon">▰</span></button>
                    <div class="ds-color-menu" data-menu="highlight" hidden>
                        <button type="button" data-command="backColor" data-value="#fff3bf" style="--swatch:#fff3bf" title="کرم ملایم"></button>
                        <button type="button" data-command="backColor" data-value="#dff4ee" style="--swatch:#dff4ee" title="سبز خیلی روشن"></button>
                        <button type="button" data-command="backColor" data-value="#e6eef6" style="--swatch:#e6eef6" title="آبی خیلی روشن"></button>
                        <button type="button" data-command="backColor" data-value="#f8e5e3" style="--swatch:#f8e5e3" title="قرمز خیلی روشن"></button>
                        <button type="button" data-command="removeFormat" data-value="highlight" title="حذف هایلایت">×</button>
                    </div>
                </div>
            </div>

            <span class="ds-tool-separator"></span>

            <div class="ds-tool-group">
                <button type="button" class="ds-tool" data-command="insertUnorderedList" title="فهرست نقطه‌ای"><span class="ds-icon">{!! $icons['ul'] ?? '' !!}</span></button>
                <button type="button" class="ds-tool" data-command="insertOrderedList" title="فهرست شماره‌دار"><span class="ds-icon">{!! $icons['ol'] ?? '' !!}</span></button>
            </div>

            <span class="ds-tool-separator"></span>

            <div class="ds-tool-group">
                <button type="button" class="ds-tool" data-command="blockquote" title="نقل‌قول"><span class="ds-icon">{!! $icons['quote'] ?? '' !!}</span></button>
                <button type="button" class="ds-tool" data-command="undo" title="بازگشت (Ctrl+Z)"><span class="ds-icon">{!! $icons['undo'] ?? '' !!}</span></button>
                <button type="button" class="ds-tool" data-command="redo" title="جلو رفتن (Ctrl+Y)"><span class="ds-icon">{!! $icons['redo'] ?? '' !!}</span></button>
            </div>

            @if($fullMode)
                <span class="ds-tool-separator"></span>
                <div class="ds-tool-group ds-full-tools">
                    <button type="button" class="ds-tool" data-command="link" title="پیوند (Ctrl+K)"><span class="ds-icon">{!! $icons['link'] ?? '' !!}</span></button>
                    <button type="button" class="ds-tool" data-command="image" title="تصویر"><span class="ds-icon">{!! $icons['image'] ?? '' !!}</span></button>
                    <button type="button" class="ds-tool" data-command="code" title="کد"><span class="ds-icon">{!! $icons['code'] ?? '' !!}</span></button>
                </div>
            @endif

            <button type="button" class="ds-tool ds-expand" data-command="fullscreen" title="تمام‌صفحه"><span class="ds-icon">{!! $icons['expand'] ?? '' !!}</span></button>
        </div>

        <div
            class="ds-editor-content"
            contenteditable="true"
            role="textbox"
            aria-multiline="true"
            dir="rtl"
            data-ds-editor-content
            data-placeholder="{{ $placeholder }}"
            spellcheck="true"
        ></div>

        <div class="ds-editor-footer">
            <span class="ds-save-state" data-ds-save-state>آماده</span>
            <span class="ds-editor-stats"><span data-ds-words>۰</span> واژه · <span data-ds-chars>۰</span> نویسه</span>
            <span class="ds-editor-hint">Ctrl+B درشت · Ctrl+I مورب · Ctrl+U زیرخط · Ctrl+Z بازگشت</span>
        </div>
    </div>

    @if(!$fullMode)
        <div class="ds-editor-note">
            <span>✓</span>
            در توضیحات محصول، تصویر، ویدئو، صوت، لینک، کد، ایموجی، تغییر فونت و چینش سفارشی غیرفعال است تا ظاهر و سئوی محصولات یکدست بماند.
        </div>
    @endif
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin-rich-editor.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('js/admin-rich-editor.js') }}" defer></script>
    @endpush
@endonce
