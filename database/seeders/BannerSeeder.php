<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Giảm giá cuối năm',
            'image_url' => 'banners/sales.jpg',
            'target_type' => 'custom',
            'link_url' => '/sales',
        ]);

        Banner::create([
            'title' => 'Giày nam mới nhất',
            'image_url' => 'banners/ThuCu.jpg',
            'target_type' => 'category',
            'target_id' => 1, // ID danh mục "Giày Nam"
        ]);

        Banner::create([
            'title' => 'Giày nữ phong cách',
            'image_url' => 'banners/OP.jpg',
            'target_type' => 'category',
            'target_id' => 2, // ID danh mục "Giày Nữ"
        ]);

        Banner::create([
            'title' => 'Sản phẩm nổi bật trong tuần',
            'image_url' => 'banners/ChoThu.jpg',
            'target_type' => 'product',
            'target_id' => 5, // ID sản phẩm cụ thể
        ]);
    }
}
