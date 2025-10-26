<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/', function () {
    return view('Home.index');
});

//CategoryController
Route::resource('categories', CategoryController::class);
