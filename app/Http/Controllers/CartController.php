<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart=session('cart',[]);$later=session('later',[]);$ids=array_map('intval',array_keys($cart));
        $products=Product::with('files')->whereIn('id',$ids)->where('is_published',true)->get();
        $laterProducts=Product::with('files')->whereIn('id',$later)->where('is_published',true)->get();
        $subtotal=(float)$products->sum('price');$discount=$this->discount($subtotal);$total=max(0,$subtotal-$discount);
        $recommendations=Product::with('files')->where('is_published',true)->whereNotIn('id',$ids)->latest()->limit(4)->get();
        return view('cart',compact('cart','products','later','laterProducts','subtotal','discount','total','recommendations'));
    }

    public function add(Product $product){abort_unless($product->is_published,404);if(auth()->check()&&$this->bought($product))return back()->with('cart_error','این فایل را قبلاً خریداری کرده‌اید.');$cart=session('cart',[]);$cart[$product->id]=1;session(['cart'=>$cart]);return back()->with('cart_added',true);}
    public function remove(Product $product){$cart=session('cart',[]);unset($cart[$product->id]);session(['cart'=>$cart]);return back();}
    public function later(Product $product){$cart=session('cart',[]);$later=session('later',[]);unset($cart[$product->id]);if(!in_array($product->id,$later))$later[]=$product->id;session(['cart'=>$cart,'later'=>$later]);return back();}
    public function moveToCart(Product $product){$later=session('later',[]);$cart=session('cart',[]);if(auth()->check()&&$this->bought($product))return back()->with('cart_error','این فایل را قبلاً خریداری کرده‌اید.');$cart[$product->id]=1;$later=array_values(array_diff($later,[$product->id]));session(['cart'=>$cart,'later'=>$later]);return back();}
    public function applyDiscount(Request $request){$data=$request->validate(['code'=>'required|string|max:50']);$code=DiscountCode::where('code',strtoupper(trim($data['code'])))->first();if(!$code||!$code->valid())return back()->with('discount_error','کد تخفیف معتبر نیست یا اعتبار آن به پایان رسیده است.');session(['discount_code'=>$code->code]);return back()->with('discount_success','کد تخفیف اعمال شد.');}
    public function removeDiscount(){session()->forget('discount_code');return back()->with('discount_success','کد تخفیف حذف شد.');}
    private function discount(float $subtotal):float{$code=session('discount_code');if(!$code)return 0;$discount=DiscountCode::where('code',$code)->first();if(!$discount||!$discount->valid()){session()->forget('discount_code');return 0;}return $discount->is_percent?min($subtotal,$subtotal*((float)$discount->amount/100)):min($subtotal,(float)$discount->amount);}
    private function bought(Product $product):bool{return Order::where('user_id',auth()->id())->whereIn('status',['paid','completed'])->whereHas('items',fn($q)=>$q->where('product_id',$product->id))->exists();}
}
