<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Services\AI\CustomerIntentService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product, CustomerIntentService $intent)
    {
        abort_unless($product->is_published,404);
        $recent=session('recent_products',[]);$recent=array_diff($recent,[$product->id]);array_unshift($recent,$product->id);session(['recent_products'=>array_slice($recent,0,8)]);
        $downloadItem=auth()->check()?OrderItem::where('product_id',$product->id)->whereHas('order',fn($q)=>$q->where('user_id',auth()->id())->where('status','paid'))->first():null;
        $product->load(['category','files','reviews'=>fn($q)=>$q->where('is_published',true)->latest(),'questions'=>fn($q)=>$q->where('is_published',true)->with(['user','answers.user'])]);
        $related=Product::where('is_published',true)->where('category_id',$product->category_id)->whereKeyNot($product->id)->latest()->take(6)->get();
        $recommended=Product::where('is_published',true)->whereKeyNot($product->id)->latest()->take(6)->get();
        $intent->record('product_view',null,$product->id);
        return view('product',compact('product','downloadItem','related','recommended'));
    }

    public function search(Request $request, CustomerIntentService $intent)
    {
        $q=trim($request->q??'');
        if($q)$intent->record('search',$q);
        $products=Product::where('is_published',1)->when($q,fn($query)=>$query->where(fn($q2)=>$q2->where('title','like',"%{$q}%")->orWhere('short_description','like',"%{$q}%")->orWhere('description','like',"%{$q}%")))->latest()->paginate(12)->withQueryString();
        return view('search',compact('products','q'));
    }
}
