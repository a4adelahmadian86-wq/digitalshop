<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\BlogPost;
class HomeController extends Controller
{
 public function index(){
  $categories=Category::where('is_active',1)->orderBy('sort_order')->orderBy('name')->get();
  $products=Product::with(['category','files'])->where('is_published',1)->latest()->take(12)->get();
  $latestPosts=class_exists(BlogPost::class)?BlogPost::where('is_published',true)->latest()->take(4)->get():collect();
  return view('home',compact('categories','products','latestPosts'));
 }
}