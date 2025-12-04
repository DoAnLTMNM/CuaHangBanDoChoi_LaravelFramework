<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của user đang đăng nhập
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Hiển thị chi tiết một đơn hàng
    public function show(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    // Thay vì xóa đơn hàng
    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền hủy đơn hàng này.');
        }

        // Chỉ cập nhật trạng thái
        $order->status = 'cancelled';
        $order->save(); // updated_date cũng sẽ tự động cập nhật nếu bạn đã thêm booted()

        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được hủy.');
    }
}
