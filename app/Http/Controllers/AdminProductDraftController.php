<?php

namespace App\Http\Controllers;

use App\Models\ProductDraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProductDraftController extends Controller
{
    public function index()
    {
        $drafts = ProductDraft::where('user_id', auth()->id())->latest()->paginate(20);
        return view('admin.products.drafts', compact('drafts'));
    }

    public function show(ProductDraft $draft): JsonResponse
    {
        abort_unless($draft->user_id === auth()->id(), 403);
        return response()->json(['ok'=>true,'draft'=>$draft]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->input('payload', []);
        if (!is_array($payload)) return response()->json(['ok'=>false,'message'=>'payload باید یک شیء JSON معتبر باشد.'],422);
        $draft = ProductDraft::create(['user_id'=>auth()->id(),'payload'=>$payload,'status'=>'draft']);
        return response()->json(['ok'=>true,'draft'=>$draft],201);
    }

    public function update(Request $request, ProductDraft $draft): JsonResponse
    {
        abort_unless($draft->user_id === auth()->id(), 403);
        $payload=$request->input('payload');
        if (!is_array($payload)) return response()->json(['ok'=>false,'message'=>'payload باید یک شیء JSON معتبر باشد.'],422);
        $draft->update(['payload'=>$payload,'status'=>$request->input('status','draft')==='saved'?'saved':'draft']);
        return response()->json(['ok'=>true,'draft'=>$draft->fresh()]);
    }

    public function destroy(ProductDraft $draft): JsonResponse
    {
        abort_unless($draft->user_id === auth()->id(), 403);
        $draft->delete();
        return response()->json(['ok'=>true]);
    }
}