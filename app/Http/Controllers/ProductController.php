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

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        return view('product.create');
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image_url' => 'nullable|url',
            'is_active' => 'required|boolean',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', compact('product'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image_url' => 'nullable|url',
            'is_active' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
