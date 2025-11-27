<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    //
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
    public function popup(Request $request)
    {
        $keyword = $request->input('q');
        if (!$keyword) return response()->json([]);

        $products = Product::query()
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhereHas('category', function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->take(5) // chỉ lấy 5 kết quả nhanh
            ->get();

        return response()->json($products);
    }
}
