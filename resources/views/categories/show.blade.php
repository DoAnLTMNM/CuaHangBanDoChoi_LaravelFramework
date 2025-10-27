@extends('layouts.app')

@section('content')
<h1>{{ $category->name }}</h1>

<div class="row">
    @forelse ($category->products as $product)
        <div class="col-md-4 mb-4">
            {{-- Toàn bộ card là 1 link --}}
            <a href="{{ route('product.show', $product->id) }}" class="card h-100 text-decoration-none text-dark">
                <img src="{{ $product->image_url ?? 'placeholder.png' }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                </div>
            </a>
        </div>
    @empty
        <p>Chưa có sản phẩm nào.</p>
    @endforelse
</div>


@endsection
