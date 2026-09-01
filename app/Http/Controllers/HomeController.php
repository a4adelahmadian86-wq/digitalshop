<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', 1)
            ->orderBy('sort_order')->get();

        $products = Product::where('is_published', 1)
            ->latest()->take(6)->get();

        return view('home', compact('categories', 'products'));
    }
}