<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->orderByDesc('id');

        // Tìm kiếm tên
        if ($request->filled('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        // Lọc trạng thái
        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('is_active', $request->status);
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo ngày tạo
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->created_date);
        }

        $products = $query->paginate(10)->appends($request->query());
        $categories = Category::where('is_active', 1)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Thêm sản phẩm
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Xử lý trạng thái hiển thị
        $data['is_active'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0;

        // Xử lý nhiều ảnh
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public'); // lưu vào storage/app/public/products
            }
            $data['images'] = json_encode($imagePaths);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }
    // Hiển thị form sửa
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->all();

        // Xử lý trạng thái hiển thị
        $data['is_active'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0;

        // Xử lý nhiều ảnh
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ
            if ($product->images) {
                foreach (json_decode($product->images) as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            // Lưu ảnh mới
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $data['images'] = json_encode($imagePaths);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }


    /**
     * Xóa sản phẩm
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
