{{-- resources/views/product/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Danh sách sản phẩm</h2>

        <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 mb-4">
                {{-- Toàn bộ card là 1 link --}}
                <a href="{{ route('products.show', $product->id) }}" class="card-product h-100 text-decoration-none text-dark">
                    <div class="card h-100">
                        <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            {{-- Chỉ hiển thị giá, không phải nút --}}
                            <p class="card-text">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                </a>
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
