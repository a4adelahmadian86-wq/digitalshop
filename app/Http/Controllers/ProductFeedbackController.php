<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductQuestion;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductFeedbackController extends Controller
{
    public function review(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(['rating' => ['required','integer','min:1','max:5'], 'body' => ['required','string','min:5','max:3000']]);
        $bought = OrderItem::where('product_id',$product->id)->whereHas('order',fn($q)=>$q->where('user_id',auth()->id())->where('status','paid'))->exists();
        abort_unless($bought,403,'ثبت نظر فقط برای خریداران این محصول فعال است.');
        ProductReview::updateOrCreate(['product_id'=>$product->id,'user_id'=>auth()->id()],$data+['is_published'=>true]);
        return back()->with('success','نظر شما ثبت شد.');
    }

    public function question(Request $request, Product $product): RedirectResponse
    {
        $data=$request->validate(['body'=>['required','string','min:5','max:3000']]);
        ProductQuestion::create(['product_id'=>$product->id,'user_id'=>auth()->id(),'body'=>$data['body'],'is_published'=>true]);
        return back()->with('success','سؤال شما ثبت شد.');
    }
}
