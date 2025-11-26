<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
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
        // Thêm 'images' và 'discount' vào with
        $query = Product::with(['category', 'images', 'discount'])->orderByDesc('id');

        // Tìm kiếm tên
        if ($request->filled('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        // Lọc trạng thái sản phẩm
        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('is_active', $request->status);
        }

        // Lọc trạng thái giảm giá
        if ($request->filled('discount_status') && in_array($request->discount_status, ['0', '1'])) {
            if ($request->discount_status == '1') {
                // Chỉ lấy sản phẩm đang giảm giá và kích hoạt
                $query->whereHas('discount', function ($q) {
                    $q->where('is_active', 1);
                });
            } else {
                // Chỉ lấy sản phẩm không có giảm giá hoặc giảm giá không kích hoạt
                $query->whereDoesntHave('discount', function ($q) {
                    $q->where('is_active', 1);
                });
            }
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
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',   // giá >= 0
            'stock' => 'nullable|integer|min:0',   // kho >= 0
            'category_id' => 'nullable|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0;
        $data['description'] = $request->input('description', '');

        $product = Product::create($data);

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
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Validate giảm giá
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
            'discount_is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') && $request->is_active == 1 ? 1 : 0;
        $data['description'] = $request->input('description', '');
        $product->update($data);

        // Xử lý ảnh cũ và ảnh mới giống như trước
        $existingImages = $request->input('existing_images', []);
        foreach ($product->images as $oldImage) {
            if (!in_array($oldImage->image, $existingImages)) {
                if (Storage::disk('public')->exists($oldImage->image)) {
                    Storage::disk('public')->delete($oldImage->image);
                }
                $oldImage->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        // -------------------
        // Xử lý giảm giá
        // -------------------
        $product->discount()->updateOrCreate(
            [], // điều kiện tìm bản ghi (một sản phẩm chỉ có một bản ghi discount)
            [
                'discount_percent' => $request->input('discount_percent'),
                'discount_amount' => $request->input('discount_amount'),
                'start_date' => $request->input('discount_start_date'),
                'end_date' => $request->input('discount_end_date'),
                'is_active' => $request->has('discount_is_active') ? 1 : 0,
            ]
        );

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

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('products', 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ], 201);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

}
