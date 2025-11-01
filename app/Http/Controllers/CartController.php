<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
     // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $userId = Auth::id(); // nếu website có đăng nhập, nếu không có có thể bỏ user_id

        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $cartItem = Cart::where('product_id', $product->id)
                        ->where('user_id', $userId)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // Hiển thị giỏ hàng
    public function index()
    {
        $userId = Auth::id(); // nếu không có đăng nhập, có thể lấy tất cả hoặc session
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        return view('cart.index', compact('cartItems'));
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(Cart $cart)
    {
        $cart->delete();
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // Cập nhật số lượng
    public function update(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Thành tiền cho sản phẩm đó
        $newSubtotal = $cartItem->product->price * $cartItem->quantity;

        // Tổng tiền toàn bộ giỏ hàng
        $total = Cart::with('product')
            ->get()
            ->sum(fn($item) => $item->product->price * $item->quantity);

        return response()->json([
            'success' => true,
            'newSubtotal' => $newSubtotal,
            'total' => $total
        ]);
    }
    
}
