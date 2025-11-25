<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Lấy tất cả danh mục đang hoạt động
        $categories = Category::where('is_active', true)->get();

        // Chia sẻ biến $categories cho tất cả view (toàn site)
        View::share('categories', $categories);
        // phân trang 
        Paginator::useBootstrapFive();
    }
}
