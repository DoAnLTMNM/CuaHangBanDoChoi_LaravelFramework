<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tháng và năm từ query string, nếu không có dùng tháng, năm hiện tại
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

        $labels = [];
        $data = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $labels[] = $day;

            $orders = Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', $day)
                ->where('status', 'completed')
                ->sum('total_price');

            $data[] = $orders;
        }

        // Tổng số đơn hàng trong tháng
        $totalOrders = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // Tổng doanh thu trong tháng
        $totalRevenue = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'completed')
            ->sum('total_price');

        // Tổng sản phẩm đang có
        $totalProducts = Product::count();

        return view('admin.dashboard', compact(
            'labels', 'data',
            'totalOrders', 'totalRevenue', 'totalProducts',
            'month', 'year'
        ));
    }
}
