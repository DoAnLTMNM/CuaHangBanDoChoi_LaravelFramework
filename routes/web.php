<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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
//Banners
Route::resource('banners', BannerController::class);
//Product
Route::resource('products', ProductController::class);
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');