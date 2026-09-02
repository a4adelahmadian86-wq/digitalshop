<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status=$request->input('status','pending');
        $query=Product::with(['category','files'])->latest();
        if($status==='all'){} elseif($status==='approved'){$query->where('approval_status','approved');} else {$query->whereIn('approval_status',['pending','needs_revision','rejected']);}
        $products=$query->paginate(15)->withQueryString();
        return view('admin.products.approvals',compact('products','status'));
    }
    public function approve(Product $product)
    {
        $product->update(['approval_status'=>'approved','approved_by'=>auth()->id(),'approved_at'=>now(),'approval_note'=>null,'is_published'=>true]);
        return back()->with('success','محصول تأیید و منتشر شد.');
    }
    public function reject(Request $request, Product $product)
    {
        $data=$request->validate(['approval_note'=>'required|string|max:2000']);
        $product->update(['approval_status'=>'rejected','approved_by'=>auth()->id(),'approved_at'=>null,'approval_note'=>$data['approval_note'],'is_published'=>false]);
        return back()->with('success','محصول رد شد و دلیل برای فروشنده ثبت شد.');
    }
    public function requestRevision(Request $request, Product $product)
    {
        $data=$request->validate(['approval_note'=>'required|string|max:2000']);
        $product->update(['approval_status'=>'needs_revision','approved_by'=>auth()->id(),'approved_at'=>null,'approval_note'=>$data['approval_note'],'is_published'=>false]);
        return back()->with('success','محصول برای اصلاح به فروشنده برگشت داده شد.');
    }
}
