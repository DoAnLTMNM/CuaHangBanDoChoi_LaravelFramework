<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
 // Khi nhấn Enter → Search Index
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $products = Product::query()
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhereHas('category', function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->with('category')
            ->paginate(12);

        return view('search.index', compact('products', 'keyword'));
    }

    // Khi gõ → popup live search
public function searchPopup(Request $request)
{
    $q = $request->query('q');
    $products = Product::with('images', 'discount')
        ->where('name', 'LIKE', "%{$q}%")
        ->get();

    $result = $products->map(function($product){
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'discounted_price' => $product->discount && $product->discount->is_active
                ? $product->price - ($product->price * $product->discount->value / 100)
                : null,
            'discount' => $product->discount && $product->discount->is_active,
            'images' => $product->images->pluck('image')->toArray(), // lấy tất cả image
        ];
    });

    return response()->json($result);
}

}
