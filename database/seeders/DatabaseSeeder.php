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

        // Tạo 5 danh mục mẫu
        Category::factory(5)->create();

        // Tạo 20 sản phẩm mẫu, mỗi sản phẩm sẽ tự gán category_id từ các category vừa tạo
        Product::factory(20)->create();
    }
}
