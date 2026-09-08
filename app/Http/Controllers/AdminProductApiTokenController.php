<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AdminProductApiTokenController extends Controller
{
    public function index(Request $request)
    {
        $tokens=$request->user()->tokens()->where('name','like','product-bot:%')->latest()->get();
        return view('admin.products.api-tokens',compact('tokens'));
    }

    public function store(Request $request)
    {
        $data=$request->validate(['name'=>['required','string','max:100']]);
        $token=$request->user()->createToken('product-bot:'.$data['name'],['products:create']);
        return back()->with('new_api_token',$token->plainTextToken)->with('success','کلید API ساخته شد. این کلید فقط هنگام ساخت نمایش داده می‌شود.');
    }

    public function destroy(Request $request, PersonalAccessToken $token)
    {
        abort_unless($token->tokenable_id===$request->user()->id && str_starts_with($token->name,'product-bot:'),404);
        $token->delete();
        return back()->with('success','کلید API لغو شد.');
    }
}
