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
        // Thêm 'images' vào with
        $query = Product::with(['category', 'images'])->orderByDesc('id');

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

        $data['is_active'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0;

        // Tạo sản phẩm
        $product = Product::create($data);

        // Xử lý nhiều ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

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
        // Cập nhật các trường cơ bản
        $data = $request->all();
        $data['is_active'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0;

        $product->update($data);

        // ----------------------------
        // XỬ LÝ ẢNH CŨ
        // ----------------------------
        // Lấy danh sách ảnh cũ còn lại từ input hidden
        $existingImages = $request->input('existing_images', []); // nếu bỏ dấu x, sẽ trả về mảng rỗng

        // Duyệt các ảnh cũ trong DB
        foreach ($product->images as $oldImage) {
            // Nếu ảnh không còn trong mảng existingImages => xóa
            if (!in_array($oldImage->image, $existingImages)) {
                // Xóa file trong storage
                if (Storage::disk('public')->exists($oldImage->image)) {
                    Storage::disk('public')->delete($oldImage->image);
                }
                // Xóa bản ghi DB
                $oldImage->delete();
            }
        }

        // ----------------------------
        // XỬ LÝ ẢNH MỚI
        // ----------------------------
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

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
