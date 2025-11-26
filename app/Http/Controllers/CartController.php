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
            $userId = Auth::id();
            $cartItems = Cart::with('product')->where('user_id', $userId)->get();
        } else {
            $sessionCart = $request->session()->get('cart', []);
            $cartItems = collect($sessionCart)->map(function ($item, $key) {
                return (object)[
                    'id' => $key, // dùng key session tạm làm id
                    'quantity' => $item['quantity'],
                    'product' => (object)[
                        'id' => $key,
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'image' => $item['image'] ?? null,
                    ],
                ];
            });
        }

        return view('cart.index', compact('cartItems'));
    }

    // Thêm sản phẩm
public function add(Request $request, $productId)
{
    $quantity = $request->input('quantity',1);

    if(Auth::check()){
        $userId = Auth::id();
        $cartItem = Cart::firstOrCreate(['user_id'=>$userId,'product_id'=>$productId]);
        $cartItem->quantity += $quantity;
        $cartItem->save();
    } else {
        $cart = $request->session()->get('cart',[]);
        if(isset($cart[$productId])){
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        $request->session()->put('cart',$cart);
    }

    $successMessage = 'Đã thêm sản phẩm vào giỏ hàng!';

    // Nếu AJAX, trả JSON + số lượng giỏ hàng
    if($request->ajax()){
        $cartCount = Auth::check() ? Cart::where('user_id',Auth::id())->sum('quantity') : array_sum($request->session()->get('cart',[]));
        return response()->json(['success'=>$successMessage,'cartCount'=>$cartCount]);
    }

    return redirect()->back()->with('success',$successMessage);
}


// Xóa sản phẩm
// CartController.php
// Cập nhật số lượng
public function update(Request $request, $id)
{
    $quantity = max(1, (int)$request->quantity);
    $successMessage = 'Cập nhật số lượng thành công!';

    if (Auth::check()) {
        $cartItem = Cart::find($id);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }
    } else {
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
        }
    }

    if ($request->ajax()) {
        return response()->json(['success' => $successMessage]);
    }

    return redirect()->back()->with('success', $successMessage);
}

// Xóa sản phẩm (giống như trước)
public function remove(Request $request, $id)
{
    $successMessage = 'Xóa sản phẩm khỏi giỏ hàng thành công!';

    if (Auth::check()) {
        $cartItem = Cart::find($id);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->delete();
        }
    } else {
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
        }
    }

    if ($request->ajax()) {
        return response()->json(['success' => $successMessage]);
    }

    return redirect()->back()->with('success', $successMessage);
}
}
