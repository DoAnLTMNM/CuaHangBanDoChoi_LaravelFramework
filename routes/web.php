<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// ==================== ADMIN AREA (CHỈ ADMIN) ====================
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin login/logout
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Admin dashboard & quản trị
    Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {

        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // CRUD sản phẩm (Admin)
        // Admin CRUD sản phẩm
        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');


        // CRUD danh mục (Admin)
        Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // CRUD banner (Admin)
        Route::get('banners', [AdminBannerController::class, 'index'])->name('banners.index');
        Route::post('banners', [AdminBannerController::class, 'store'])->name('banners.store');
        Route::put('banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
        Route::delete('banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');

        Route::post('products/upload-image', [AdminProductController::class, 'uploadImage'])->name('products.uploadImage');
    });
});



// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// VNPay
Route::get('/checkout/vnpay/{order}', [CheckoutController::class,'vnpayPayment'])->name('vnpay.payment');
Route::get('/checkout/vnpay-return', [CheckoutController::class,'vnpayReturn'])->name('vnpay.return');

// Momo
Route::get('/checkout/momo/{order}', [CheckoutController::class,'momoPayment'])->name('momo.payment');
Route::post('/checkout/momo-ipn', [CheckoutController::class,'momoIpn'])->name('momo.ipn');


});

// ==================== ROUTE CÔNG KHAI ====================
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


require __DIR__ . '/auth.php';
