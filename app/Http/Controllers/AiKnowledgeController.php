<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AI\ProductKnowledgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiKnowledgeController extends Controller
{
    public function evidence(Request $request, Product $product, ProductKnowledgeService $knowledge): JsonResponse
    {
        abort_unless($product->is_published, 404);
        $query = trim((string) $request->input('q'));
        abort_if($query === '', 422, 'عبارت جستجو خالی است.');
        return response()->json(['ok' => true, 'product_id' => $product->id, 'evidence' => $knowledge->evidence($product, $query)]);
    }
}
