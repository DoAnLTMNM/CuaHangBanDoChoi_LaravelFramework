<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả danh mục.
     */
    public function index(Request $request)
    {
        $query = Category::with('parent')->orderByDesc('id');

        // Tìm kiếm theo tên
        if ($request->filled('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('is_active', $request->status);
        }

        // Lọc theo danh mục cha
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // Lọc theo khoảng ngày tạo
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->created_date);
        }


        $categories = $query->paginate(10)->appends($request->query());

        // Xem chi tiết category từ popup
        $viewCategory = null;
        if ($request->has('view_id')) {
            $viewCategory = Category::with('products')->find($request->view_id);
        }

        // Lấy danh sách category cha để hiển thị combobox
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('admin.categories.index', compact('categories', 'viewCategory', 'parentCategories'));
    }



    /**
     * Xử lý thêm mới danh mục.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // ⭐ SET TRẠNG THÁI
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }


    /**
     * Cập nhật danh mục.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // ⭐ SET TRẠNG THÁI
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }


    /**
     * Xóa danh mục.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }
}
