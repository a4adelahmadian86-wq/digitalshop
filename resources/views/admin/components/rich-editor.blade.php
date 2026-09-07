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
    $icon = function (string $path) {
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
    };
@endphp

<div class="ds-rich-editor" id="{{ $editorId }}" data-ds-editor data-mode="{{ $mode }}" data-name="{{ $name }}" data-autosave="1" style="--ds-editor-height: {{ (int) $height }}px">
    <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}" data-ds-editor-value>

    <div class="ds-editor-shell">
        <div class="ds-editor-toolbar" role="toolbar" aria-label="ابزارهای ویرایش متن">
            <div class="ds-tool-group">
                <button type="button" class="ds-tool ds-tool-select" data-command="format" data-value="p" title="سبک متن"><span class="ds-tool-label">متن عادی</span><span class="ds-chevron">⌄</span></button>
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
                <button type="button" class="ds-tool" data-command="bold" title="درشت (Ctrl+B)"><span class="ds-icon">{!! $icon('<path d="M7 5h6a3 3 0 0 1 0 6H7zm0 6h7a3 3 0 0 1 0 6H7zM7 5v12"/>') !!}</span></button>
                <button type="button" class="ds-tool" data-command="italic" title="مورب (Ctrl+I)"><span class="ds-icon">{!! $icon('<path d="M10 5h8M6 19h8M14 5 10 19"/>') !!}</span></button>
                <button type="button" class="ds-tool" data-command="underline" title="زیرخط (Ctrl+U)"><span class="ds-icon">{!! $icon('<path d="M7 5v6a5 5 0 0 0 10 0V5M5 19h14"/>') !!}</span></button>
            </div>
            <span class="ds-tool-separator"></span>
            <div class="ds-tool-group">
                <div class="ds-color-wrap">
                    <button type="button" class="ds-tool ds-color-button" data-color-menu="text" title="رنگ متن"><span class="ds-icon ds-color-letter">A</span></button>
                    <div class="ds-color-menu" data-menu="text" hidden>
                        <button type="button" data-command="foreColor" data-value="#1f2937" style="--swatch:#1f2937" title="ذغالی"></button><button type="button" data-command="foreColor" data-value="#315c7c" style="--swatch:#315c7c" title="آبی برند"></button><button type="button" data-command="foreColor" data-value="#176b5b" style="--swatch:#176b5b" title="سبز آرام"></button><button type="button" data-command="foreColor" data-value="#8a5a00" style="--swatch:#8a5a00" title="طلایی تیره"></button><button type="button" data-command="foreColor" data-value="#9f3a38" style="--swatch:#9f3a38" title="قرمز کنترل‌شده"></button><button type="button" data-command="removeFormat" data-value="color" title="حذف رنگ">×</button>
                    </div>
                </div>
                <div class="ds-color-wrap">
                    <button type="button" class="ds-tool ds-color-button" data-color-menu="highlight" title="هایلایت متن"><span class="ds-icon ds-highlight-icon">▰</span></button>
                    <div class="ds-color-menu" data-menu="highlight" hidden>
                        <button type="button" data-command="backColor" data-value="#fff3bf" style="--swatch:#fff3bf" title="کرم ملایم"></button><button type="button" data-command="backColor" data-value="#dff4ee" style="--swatch:#dff4ee" title="سبز خیلی روشن"></button><button type="button" data-command="backColor" data-value="#e6eef6" style="--swatch:#e6eef6" title="آبی خیلی روشن"></button><button type="button" data-command="backColor" data-value="#f8e5e3" style="--swatch:#f8e5e3" title="قرمز خیلی روشن"></button><button type="button" data-command="removeFormat" data-value="highlight" title="حذف هایلایت">×</button>
                    </div>
                </div>
            </div>
            <span class="ds-tool-separator"></span>
            <div class="ds-tool-group">
                <button type="button" class="ds-tool" data-command="insertUnorderedList" title="فهرست نقطه‌ای"><span class="ds-icon">{!! $icon('<path d="M8 6h13M8 12h13M8 18h13"/><path d="M3 6h.01M3 12h.01M3 18h.01"/>') !!}</span></button>
                <button type="button" class="ds-tool" data-command="insertOrderedList" title="فهرست شماره‌دار"><span class="ds-icon">{!! $icon('<path d="M9 6h12M9 12h12M9 18h12"/><path d="M4 4v4M3 4h2M3 8h2M3 10h2l-2 4h2M3 17h2l-2 3h2"/>') !!}</span></button>
            </div>
            <span class="ds-tool-separator"></span>
            <div class="ds-tool-group">
                <button type="button" class="ds-tool" data-command="blockquote" title="نقل‌قول"><span class="ds-icon">{!! $icon('<path d="M9 11H5a3 3 0 0 0-3 3v2h7v-5zM22 11h-4a3 3 0 0 0-3 3v2h7v-5z"/>') !!}</span></button>
                <button type="button" class="ds-tool" data-command="undo" title="بازگشت (Ctrl+Z)"><span class="ds-icon">{!! $icon('<path d="M9 14 4 9l5-5"/><path d="M4 9h9a7 7 0 0 1 7 7v1"/>') !!}</span></button>
                <button type="button" class="ds-tool" data-command="redo" title="جلو رفتن (Ctrl+Y)"><span class="ds-icon">{!! $icon('<path d="m15 14 5-5-5-5"/><path d="M20 9h-9a7 7 0 0 0-7 7v1"/>') !!}</span></button>
            </div>
            @if($fullMode)
                <span class="ds-tool-separator"></span>
                <div class="ds-tool-group ds-full-tools">
                    <button type="button" class="ds-tool" data-command="link" title="پیوند (Ctrl+K)"><span class="ds-icon">{!! $icon('<path d="M10 13a5 5 0 0 0 7.07.07l2-2a5 5 0 0 0-7.07-7.07l-1.15 1.15"/><path d="M14 11a5 5 0 0 0-7.07-.07l-2 2A5 5 0 0 0 12 20l1.15-1.15"/>') !!}</span></button>
                    <button type="button" class="ds-tool" data-command="image" title="تصویر"><span class="ds-icon">{!! $icon('<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/>') !!}</span></button>
                    <button type="button" class="ds-tool" data-command="code" title="کد"><span class="ds-icon">{!! $icon('<path d="m8 9-4 3 4 3M16 9l4 3-4 3M14 5l-4 14"/>') !!}</span></button>
                </div>
            @endif
            <button type="button" class="ds-tool ds-expand" data-command="fullscreen" title="تمام‌صفحه"><span class="ds-icon">{!! $icon('<path d="M8 3H5a2 2 0 0 0-2 2v3M16 3h3a2 2 0 0 1 2 2v3M8 21H5a2 2 0 0 1-2-2v-3M16 21h3a2 2 0 0 0 2-2v-3"/>') !!}</span></button>
        </div>

        <div class="ds-editor-content" contenteditable="true" role="textbox" aria-multiline="true" dir="rtl" data-ds-editor-content data-placeholder="{{ $placeholder }}" spellcheck="true"></div>
        <div class="ds-editor-footer"><span class="ds-save-state" data-ds-save-state>آماده</span><span class="ds-editor-stats"><span data-ds-words>۰</span> واژه · <span data-ds-chars>۰</span> نویسه</span><span class="ds-editor-hint">Ctrl+B درشت · Ctrl+I مورب · Ctrl+U زیرخط · Ctrl+Z بازگشت</span></div>
    </div>
    @if(!$fullMode)<div class="ds-editor-note"><span>✓</span> در توضیحات محصول، تصویر، ویدئو، صوت، لینک، کد، ایموجی، تغییر فونت و چینش سفارشی غیرفعال است تا ظاهر و سئوی محصولات یکدست بماند.</div>@endif
</div>

@once
    @push('styles')<link rel="stylesheet" href="{{ asset('css/admin-rich-editor.css') }}">@endpush
    @push('scripts')<script src="{{ asset('js/admin-rich-editor.js') }}" defer></script>@endpush
@endonce
