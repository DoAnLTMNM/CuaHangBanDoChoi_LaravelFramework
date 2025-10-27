<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Tạo danh mục cha
        $parents = Category::factory(3)->create();

        // Tạo danh mục con
        $parents->each(function ($parent) {
            Category::factory(rand(2, 4))->create([
                'parent_id' => $parent->id
            ]);
        });

        // Lấy toàn bộ danh mục
        $categories = Category::all();

        // Tạo sản phẩm và gán danh mục ngẫu nhiên
        Product::factory(20)->create()->each(function ($product) use ($categories) {
            $product->update([
                'category_id' => $categories->random()->id,
            ]);
        });

        // 👉 Chỉ gọi ProductSeeder nếu cần seed thêm dữ liệu mẫu đặc biệt
        // $this->call(ProductSeeder::class);
    }
}
