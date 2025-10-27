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

        // Tạo danh mục cha và con
        $parents = Category::factory(3)->create();

        $parents->each(function ($parent) {
            Category::factory(rand(2, 4))->create([
                'parent_id' => $parent->id
            ]);
        });

        // Lấy tất cả danh mục
        $categories = Category::all();

        // Tạo sản phẩm và gán category_id random
        Product::factory(20)->create()->each(function ($product) use ($categories) {
            $product->category_id = $categories->random()->id;
            $product->save();
        });

        $this->call(ProductSeeder::class);
    }
}
