@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mt-4">
    <h2>{{ $product->name }}</h2>
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h4>Giá: {{ number_format($product->price, 0, ',', '.') }}₫</h4>
            <p>Trạng thái: {{ $product->is_active ? 'Hoạt động' : 'Ngưng hoạt động' }}</p>
            <p>Mô tả: {{ $product->description ?? 'Chưa có mô tả' }}</p>
        </div>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection
