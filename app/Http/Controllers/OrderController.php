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

    // Xóa đơn hàng (nếu muốn)
    public function destroy(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa đơn hàng này.');
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Xóa đơn hàng thành công.');
    }
}
