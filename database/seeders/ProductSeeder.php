<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $faker = \Faker\Factory::create();

        // Lấy 1 danh mục ngẫu nhiên để gán (nếu không có thì null)
        $category = Category::inRandomOrder()->first();

        // Tạo sản phẩm
        $product = Product::create([
            'name' => 'Bàn phím cơ GravaStar Mercury V75 Pro Neon Graffiti',
            'slug' => 'gravarstar-mercury-v75-pro-neon-graffiti',
            'description' => 'Hàng chất lượng cao, thiết kế Neon Graffiti...',
            'price' => 6890000,
            'stock' => 10,
            'brand' => 'GravaStar',
            'is_active' => true,
            'category_id' => $category?->id,
            // dùng URL ảnh giả (không lưu file vào storage)
            'image' => $faker->imageUrl(640, 480, 'products', true, 'Faker'),
        ]);

        // Items đi kèm
        $items = [
            ['item_name' => 'Bàn phím cơ GravaStar Mercury V75 Pro Neon Graffiti', 'quantity' => 1],
            ['item_name' => 'Cáp USB Type-A to Type-C', 'quantity' => 1],
            ['item_name' => 'Dụng cụ nhổ keycap và switch 2-in-1', 'quantity' => 1],
            ['item_name' => 'Cọ vệ sinh', 'quantity' => 1],
            ['item_name' => 'Extra Switches', 'quantity' => 4],
            ['item_name' => 'Sách hướng dẫn', 'quantity' => 1],
            ['item_name' => 'Khăn lau', 'quantity' => 1],
        ];

        foreach ($items as $item) {
            $product->items()->create($item);
        }

        // Features
        $features = [
            'Tần số quét 8000Hz, độ trễ 0.125ms',
            'Switch từ tính Hall Effect, Actuation Point 0.2–3.7mm',
            'Rapid Trigger Mode cho phản hồi tức thì',
            'Khung nhôm CNC với thiết kế Graffiti Neon',
            'Âm thanh "Thock" êm tai, giảm rung',
        ];

        foreach ($features as $feature) {
            $product->features()->create(['feature' => $feature]);
        }
    }
}
