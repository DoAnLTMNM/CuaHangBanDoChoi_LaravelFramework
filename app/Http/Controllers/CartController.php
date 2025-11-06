<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index(Request $request)
    {
        if (Auth::check()) {
            // Người dùng đã đăng nhập -> lấy từ DB
            $userId = Auth::id();
            $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        } else {
            // Người dùng chưa đăng nhập -> lấy từ session
            $sessionCart = $request->session()->get('cart', []);

            // Chuyển array session thành collection object giống Eloquent
            $cartItems = collect($sessionCart)->map(function ($item, $key) {
                return (object)[
                    'id' => $key, // key của session dùng làm id tạm
                    'quantity' => $item['quantity'],
                    'product' => (object)[
                        'id' => $key, // dùng key tạm làm product id
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'image' => $item['image'] ?? null,
                    ],
                ];
            });
        }

        return view('cart.index', compact('cartItems'));
    }
    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        if (Auth::check()) {
            $userId = Auth::id();

            // Merge giỏ hàng session trước khi lưu database
            if ($request->session()->has('cart')) {
                $sessionCart = $request->session()->get('cart');
                foreach ($sessionCart as $id => $item) {
                    $cartItem = Cart::firstOrNew([
                        'user_id' => $userId,
                        'product_id' => $id
                    ]);
                    $cartItem->quantity = ($cartItem->quantity ?? 0) + $item['quantity'];
                    $cartItem->save();
                }
                $request->session()->forget('cart');
            }

            $cartItem = Cart::firstOrNew([
                'user_id' => $userId,
                'product_id' => $product->id
            ]);
            $cartItem->quantity = ($cartItem->quantity ?? 0) + $quantity;
            $cartItem->save();
        } else {
            // Lưu giỏ hàng vào session cho user chưa đăng nhập
            $cart = $request->session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image
                ];
            }
            $request->session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(Request $request, $id)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->where('product_id', $id)->delete();
        } else {
            $cart = $request->session()->get('cart', []);
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Xóa sản phẩm khỏi giỏ hàng thành công!');
    }

    // Cập nhật số lượng
    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity', 1);

        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $id)->firstOrFail();
            $cartItem->quantity = $quantity;
            $cartItem->save();
        } else {
            $cart = $request->session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $quantity;
                $request->session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }
}
