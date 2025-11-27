@extends('layouts.app')

@section('title','Thanh toán')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thanh toán</h2>

    <div class="row">
        <!-- Giỏ hàng -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Giỏ hàng của bạn</div>
                <ul class="list-group list-group-flush">
                    @php $total=0; @endphp
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->product;
                            $qty = $item->quantity;
                            $price = $product->price;
                            $discounted = $item->discounted_price;
                            $subtotal = $discounted*$qty;
                            $total+=$subtotal;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                Số lượng: {{ $qty }}<br>
                                @if($discounted < $price)
                                    <span class="text-muted" style="text-decoration: line-through;">{{ number_format($price) }}₫</span>
                                    <span class="text-danger">{{ number_format($discounted) }}₫</span>
                                @else
                                    <span>{{ number_format($price) }}₫</span>
                                @endif
                            </div>
                            <span>{{ number_format($subtotal) }}₫</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        <span>Tổng tiền</span>
                        <span>{{ number_format($total) }}₫</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Thông tin khách hàng & Thanh toán -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    Thông tin khách hàng & Phương thức thanh toán
                </div>
                <div class="card-body">
                    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" style="text-align: left;">
                        @csrf
                        <div class="mb-3">
                            <label>Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" value="{{ old('fullname',$user->name ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email',$user->email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label>Tỉnh/Thành phố</label>
                            <select name="province" class="form-control" id="province" required>
                                <option value="">--Chọn Tỉnh/Thành--</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p['code'] }}">{{ $p['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Quận/Huyện</label>
                            <select name="district" class="form-control" id="district" required>
                                <option value="">--Chọn Quận/Huyện--</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Phường/Xã</label>
                            <select name="ward" class="form-control" id="ward" required>
                                <option value="">--Chọn Phường/Xã--</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ chi tiết</label>
                            <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="mb-3">
                            <label>Phương thức thanh toán</label>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-outline-primary payment-btn" data-value="COD">COD</button>
                                <button type="button" class="btn btn-outline-success payment-btn" data-value="VNPAY">VNPay</button>
                                <button type="button" class="btn btn-outline-warning payment-btn" data-value="MOMO">Momo</button>
                            </div>
                            <input type="hidden" name="payment_method" id="payment_method" value="COD" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Đặt hàng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const province = document.getElementById('province');
    const district = document.getElementById('district');
    const ward = document.getElementById('ward');

    province.addEventListener('change', function(){
        fetch('https://provinces.open-api.vn/api/p/'+this.value+'?depth=2')
        .then(res=>res.json())
        .then(data=>{
            district.innerHTML='<option value="">--Chọn Quận/Huyện--</option>';
            ward.innerHTML='<option value="">--Chọn Phường/Xã--</option>';
            data.districts.forEach(d=>{
                district.innerHTML+=`<option value="${d.code}">${d.name}</option>`;
            });
        });
    });

    district.addEventListener('change', function(){
        fetch('https://provinces.open-api.vn/api/d/'+this.value+'?depth=2')
        .then(res=>res.json())
        .then(data=>{
            ward.innerHTML='<option value="">--Chọn Phường/Xã--</option>';
            data.wards.forEach(w=>{
                ward.innerHTML+=`<option value="${w.code}">${w.name}</option>`;
            });
        });
    });

    // Payment selection
    const paymentBtns = document.querySelectorAll('.payment-btn');
    const paymentInput = document.getElementById('payment_method');
    paymentBtns.forEach(btn=>{
        btn.addEventListener('click', ()=>{
            paymentInput.value = btn.dataset.value;
            paymentBtns.forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    // Validate before submit
    const form = document.getElementById('checkoutForm');
    form.addEventListener('submit', function(e){
        if(!paymentInput.value){
            e.preventDefault();
            alert('Vui lòng chọn phương thức thanh toán!');
        }
    });
});
</script>
@endsection
