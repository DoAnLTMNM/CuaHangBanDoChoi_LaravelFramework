<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả danh mục.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children', 'products'])
            ->get();

        return view('categories.index', compact('categories'));
    }
    // Hiển thị chi tiết một danh mục.
    public function show($slug)
    {
        $category = Category::with('products')->where('slug', $slug)->firstOrFail();

        return view('categories.show', compact('category'));
    }
    // Hiển thị form thêm danh mục.
    public function create()
    {
        // Lấy tất cả danh mục để chọn làm danh mục cha
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }
    // Xử lý thêm mới danh mục.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        // Nếu không nhập slug thì tự tạo từ name
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }
    // Hiển thị form chỉnh sửa danh mục.
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // Lấy danh sách danh mục khác để làm danh mục cha
        $categories = Category::where('id', '!=', $id)->get();

        return view('categories.edit', compact('category', 'categories'));
    }
    // Cập nhật thông tin danh mục.
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        // Nếu không nhập slug thì tự tạo lại
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }
    // Xóa danh mục.
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }
}
