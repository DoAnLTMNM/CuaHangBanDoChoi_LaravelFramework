<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');


// Route cho admin (cần đăng nhập + role admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('products/create', [ProductController::class, 'create']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}/edit', [ProductController::class, 'edit']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
});

// Route công khai, không cần đăng nhập
// Route hiển thị danh sách sản phẩm
Route::get('products', [ProductController::class, 'index'])->name('products.index');
// Route hiển thị chi tiết sản phẩm
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
// Route::get('products/{id}', [ProductController::class, 'show'])->name('product.show');

// CartController
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

require __DIR__.'/auth.php';
