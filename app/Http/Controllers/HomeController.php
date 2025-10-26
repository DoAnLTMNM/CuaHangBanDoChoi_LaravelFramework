<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->get();
        // $categories = Category::where('is_active', true)->take(9)->get();
        $products = Product::where('is_active', true)->take(8)->get();

        return view('home.index', compact('banners', 'products'));
    }
}
