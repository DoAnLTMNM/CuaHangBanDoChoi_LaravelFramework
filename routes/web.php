<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/', function () {
    return view('Home.index');
});
Route::get('/', [HomeController::class, 'index'])->name('home');

//CategoryController
Route::resource('categories', CategoryController::class);
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
//Banners
Route::resource('banners', BannerController::class);
//Product
Route::resource('products', ProductController::class);
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');


//cartController
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');

// Trang chi tiết sản phẩm
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
// Thêm sản phẩm vào giỏ hàng
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');