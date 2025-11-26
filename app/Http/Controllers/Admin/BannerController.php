<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Hiển thị danh sách banner
     */
    public function index(Request $request)
    {
        $query = Banner::orderByDesc('id');

        // Lọc trạng thái
        if ($request->filled('is_active') && in_array($request->is_active, ['0', '1'])) {
            $query->where('is_active', $request->is_active);
        }

        // Tìm kiếm theo tiêu đề
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $banners = $query->paginate(10)->appends($request->query());

        return view('admin.banners.index', compact('banners'));
    }


    /**
     * Hiển thị form tạo banner
     */
    public function create()
    {
        return view('admin.banners.index');
    }

    /**
     * Lưu banner mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'target_type' => 'nullable|string|max:50',
            'target_id' => 'nullable|integer',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean', // trạng thái có thể null
        ]);

        // Chuyển trạng thái checkbox về 0/1
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa banner
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.index', compact('banner'));
    }

    /**
     * Cập nhật banner
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'target_type' => 'nullable|string|max:50',
            'target_id' => 'nullable|integer',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        // Checkbox trạng thái
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image_url')) {
            if ($banner->image_url && Storage::disk('public')->exists($banner->image_url)) {
                Storage::disk('public')->delete($banner->image_url);
            }
            $validated['image_url'] = $request->file('image_url')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    /**
     * Xóa banner
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image_url && Storage::disk('public')->exists($banner->image_url)) {
            Storage::disk('public')->delete($banner->image_url);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Xóa banner thành công!');
    }
}
