<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::paginate(12); // phân trang
        return view('product.index', compact('products'));
    }

    // // Hiển thị form thêm sản phẩm
    // public function create()
    // {
    //     return view('product.create');
    // }

    // // Lưu sản phẩm mới
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'description' => 'nullable|string',
    //         'stock' => 'nullable|integer',
    //         'brand' => 'nullable|string|max:255',
    //         'is_active' => 'required|boolean',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $data = $request->all();

    //     // Xử lý upload hình ảnh
    //     if ($request->hasFile('image')) {
    //         $path = $request->file('image')->store('products', 'public');
    //         $data['image'] = $path; // lưu đường dẫn vào DB
    //     }

    //     Product::create($data);

    //     return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    // }

    // Hiển thị chi tiết sản phẩm
    // public function show($id)
    // {
    //     $product = Product::findOrFail($id);
    //     return view('product.show', compact('product'));
    // }

    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);
    //     return view('product.edit', compact('product'));
    // }

    // // Cập nhật sản phẩm
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'description' => 'nullable|string',
    //         'stock' => 'nullable|integer',
    //         'brand' => 'nullable|string|max:255',
    //         'is_active' => 'required|boolean',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $product = Product::findOrFail($id);
    //     $data = $request->all();

    //     // Xử lý upload hình ảnh mới (nếu có)
    //     if ($request->hasFile('image')) {
    //         $path = $request->file('image')->store('products', 'public');
    //         $data['image'] = $path;
    //     }

    //     $product->update($data);

    //     return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    // }

    // // Xóa sản phẩm
    // public function destroy($id)
    // {
    //     $product = Product::findOrFail($id);
    //     $product->delete();

    //     return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    // }


    // 

        public function getSaleProducts($limit = 5)
    {
        return Product::with('images', 'discount')
            ->whereHas('discount', function ($q) {
                $q->where('is_active', 1)
                    ->where(function ($q2) {
                        $q2->whereNotNull('discount_percent')
                            ->orWhereNotNull('discount_amount');
                    });
            })
            ->take($limit)
            ->get();
    }

    public function getLatestProducts($limit = 8)
    {
        return Product::with('images')
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();
    }

        public function show($id)
    {
        $product = \App\Models\Product::with('category')->findOrFail($id);

        // Tạo breadcrumbs
        $breadcrumbs = [
            ['title' => 'Trang chủ', 'url' => route('home')],
        ];

        if ($product->category) {
            $breadcrumbs[] = [
                'title' => $product->category->name,
                'url' => route('products.index', ['category' => $product->category->id]),
            ];
        }

        $breadcrumbs[] = [
            'title' => $product->name,
            'url' => '', // last item
        ];

        // Truyền cả product và breadcrumbs vào view
        return view('product.show', compact('product', 'breadcrumbs'));
    }
}
