@props(['product','reason'=>null])
<a class="store-product-card" href="{{ route('product.show', $product) }}">
    <div class="store-product-card__media"><img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" loading="lazy"></div>
    <div class="store-product-card__body">
        <strong>{{ $product->title }}</strong>
        <span>{{ number_format($product->price) }} تومان</span>
        @if($reason)<em>{{ $reason }}</em>@endif
    </div>
</a>
