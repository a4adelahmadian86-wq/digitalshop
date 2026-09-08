<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->validate([
            'title'=>['required','string','max:255'],'slug'=>['nullable','string','max:255','unique:products,slug'],
            'category_id'=>['required','integer','exists:categories,id'],'price'=>['required','numeric','min:0'],
            'short_description'=>['nullable','string'],'description'=>['nullable','string'],
            'thumbnail'=>['nullable','string','max:255'],'file_name'=>['nullable','string','max:255'],
            'file_path'=>['nullable','string','max:1000'],'storage_provider_id'=>['nullable','integer','exists:storage_providers,id'],
            'is_published'=>['nullable','boolean'],
        ]);
        $data['slug']=$data['slug']??Str::slug($data['title']).'-'.Str::lower(Str::random(6));
        $data['is_published']=false;
        $product=Product::create($data);
        return response()->json(['ok'=>true,'product'=>$product],201);
    }
}
