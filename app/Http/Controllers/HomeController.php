<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $productsQuery = Product::with(['category', 'files'])->where('is_published', true);

        $products = (clone $productsQuery)->latest('id')->take(12)->get();
        $latestProducts = (clone $productsQuery)->latest('id')->take(8)->get();
        $bestSellingProducts = (clone $productsQuery)->withCount('orderItems')->orderByDesc('order_items_count')->latest('id')->take(8)->get();
        $usefulProducts = (clone $productsQuery)->whereNotNull('short_description')->where('short_description', '<>', '')->latest('id')->take(8)->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->when(\Illuminate\Schema\Builder::hasColumn('categories', 'status'), fn ($q) => $q->where('status', true))
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $popularCategories = Category::query()
            ->where('is_active', true)
            ->when(\Illuminate\Schema\Builder::hasColumn('categories', 'status'), fn ($q) => $q->where('status', true))
            ->whereNull('parent_id')
            ->withCount(['products as published_products_count' => fn ($q) => $q->where('is_published', true)])
            ->orderByDesc('published_products_count')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $quickDefinitions = [
            ['title' => 'دانشگاه و یادگیری', 'description' => 'منابع و فایل‌های کاربردی برای یادگیری، دانشگاه و پیشرفت تحصیلی', 'slug' => 'academic-projects', 'image' => 'categories/university-learning.svg', 'accent' => 'blue'],
            ['title' => 'کار و استخدام', 'description' => 'رزومه، منابع حرفه‌ای و ابزارهای کاربردی برای مسیر شغلی', 'slug' => 'employment', 'image' => 'categories/career-employment.svg', 'accent' => 'teal'],
            ['title' => 'کسب‌وکار', 'description' => 'منابع و ابزارهایی برای ساخت، مدیریت و رشد کسب‌وکار', 'slug' => 'business-entrepreneurship', 'image' => 'categories/business.svg', 'accent' => 'navy'],
            ['title' => 'فناوری', 'description' => 'فایل‌ها و منابع کاربردی در حوزه فناوری، نرم‌افزار و ابزارهای دیجیتال', 'slug' => 'programming', 'image' => 'categories/technology.svg', 'accent' => 'cyan'],
            ['title' => 'طراحی و محتوا', 'description' => 'منابع خلاقانه برای طراحی، تولید محتوا و کارهای دیجیتال', 'slug' => 'content-social-media', 'image' => 'categories/design-content.svg', 'accent' => 'violet'],
            ['title' => 'کتاب و منابع', 'description' => 'کتاب‌ها، فایل‌های مطالعاتی و منابع ارزشمند برای یادگیری و مطالعه', 'slug' => 'books-pdf', 'image' => 'categories/books-resources.svg', 'accent' => 'rose'],
        ];

        $quickCategories = collect($quickDefinitions)->map(function ($item) {
            $category = Category::where('slug', $item['slug'])->first();
            return array_merge($item, ['id' => $category?->id]);
        });

        $latestPosts = class_exists(BlogPost::class)
            ? BlogPost::where('is_published', true)->latest()->take(4)->get()
            : collect();

        return view('home', compact(
            'categories',
            'popularCategories',
            'quickCategories',
            'products',
            'bestSellingProducts',
            'usefulProducts',
            'latestProducts',
            'latestPosts'
        ));
    }
}
