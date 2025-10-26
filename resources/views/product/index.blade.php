{{-- resources/views/product/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Danh sách sản phẩm</h2>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Chưa có sản phẩm nào.</p>
        @endforelse
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
