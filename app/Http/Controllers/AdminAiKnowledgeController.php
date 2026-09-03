<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AI\ProductKnowledgeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAiKnowledgeController extends Controller
{
    public function index(): View
    {
        $products = Product::withCount('files')->latest()->paginate(30);
        return view('admin.ai.knowledge', compact('products'));
    }

    public function indexProduct(Product $product, ProductKnowledgeService $knowledge): RedirectResponse
    {
        $result = $knowledge->index($product);
        return back()->with('success', "پایگاه دانش محصول به‌روزرسانی شد: {$result['extracted_files']} فایل از {$result['files']} فایل استخراج شد.");
    }
}
