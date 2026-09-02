@props([
    'name',
    'size' => 20,
    'class' => '',
])
@php
    $paths = [
        'arrow-left' => '<path d="M15 18l-6-6 6-6"/>',
        'arrow-right' => '<path d="M9 18l6-6-6-6"/>',
        'cart' => '<path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L20 7H6"/><circle cx="9" cy="20" r="1"/><circle cx="17" cy="20" r="1"/>',
        'search' => '<circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/>',
        'user' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/>',
        'file' => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>',
        'pdf' => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v5h5"/><path d="M8.5 17c2-4 3-6 4.5-8 0 3 1 5 3 6M8 18h8"/>',
        'word' => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v5h5M8 12l1.5 5 1.5-4 1.5 4 1.5-5"/>',
        'download' => '<path d="M12 3v11"/><path d="M7 10l5 5 5-5"/><path d="M4 20h16"/>',
        'eye' => '<path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/><circle cx="12" cy="12" r="2.5"/>',
        'lock' => '<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        'check' => '<path d="M5 12l4 4L19 6"/>',
        'warning' => '<path d="M12 3l9 17H3z"/><path d="M12 9v5M12 17h.01"/>',
        'ai' => '<path d="M12 3l1.5 4.5L18 9l-4.5 1.5L12 15l-1.5-4.5L6 9l4.5-1.5z"/><path d="M18 15l.7 2.3L21 18l-2.3.7L18 21l-.7-2.3L15 18l2.3-.7z"/>',
        'highlight' => '<path d="M4 20h4L19 9l-4-4L4 16v4zM14 6l4 4"/>',
        'note' => '<path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5"/>',
        'zoom-in' => '<circle cx="10.5" cy="10.5" r="6"/><path d="M15 15l5 5M10.5 7.5v6M7.5 10.5h6"/>',
        'zoom-out' => '<circle cx="10.5" cy="10.5" r="6"/><path d="M15 15l5 5M7.5 10.5h6"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
    ];
    $svg = $paths[$name] ?? $paths['file'];
@endphp
<svg {{ $attributes->merge(['class' => 'ds-icon '.$class, 'width' => $size, 'height' => $size, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>{!! $svg !!}</svg>
