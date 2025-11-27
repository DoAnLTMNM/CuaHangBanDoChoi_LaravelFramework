@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <h2 class="mb-4">Thống kê - Tháng {{ $month }}/{{ $year }}</h2>

    <!-- Form lọc tháng/năm -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-auto">
            <select name="month" class="form-select">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}</option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <select name="year" class="form-select">
                @for($y = date('Y') - 5; $y <= date('Y'); $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>

    <!-- Info boxes -->
    <div class="row mb-4">
        <div class="col-md-4 mb-2">
            <div class="p-3 bg-primary text-white rounded shadow-sm">
                <h5>Đơn hàng trong tháng</h5>
                <h2>{{ $totalOrders }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="p-3 bg-success text-white rounded shadow-sm">
                <h5>Tổng doanh thu</h5>
                <h2>{{ number_format($totalRevenue, 0, ',', '.') }}₫</h2>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="p-3 bg-warning text-white rounded shadow-sm">
                <h5>Tổng sản phẩm</h5>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="card">
        <div class="card-header">
            Doanh thu tháng {{ $month }}/{{ $year }}
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="150"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($data),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + '₫';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
