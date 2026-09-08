<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->validate([
            'title'=>['required','string','max:255'],'slug'=>['nullable','string','max:255','unique:products,slug'],
            'category_id'=>['required','integer','exists:categories,id'],'price'=>['required','numeric','min:0'],
            'short_description'=>['nullable','string'],'description'=>['nullable','string'],
            'file_format'=>['nullable','string','max:50'],'page_count'=>['nullable','integer','min:0'],
        ]);
        $product=Product::create($data+['status'=>'draft']);
        return response()->json(['ok'=>true,'product'=>$product],201);
    }
}
