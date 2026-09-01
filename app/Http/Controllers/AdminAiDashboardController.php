<?php

namespace App\Http\Controllers;

use App\Models\AiProductAnalysis;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\View\View;

class AdminAiDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pending' => Product::whereIn('ai_status', ['not_checked','pending','pending_review','pending_ai'])->count(),
            'approved' => Product::where('ai_status','approved')->count(),
            'flagged' => Product::whereIn('ai_status',['blocked','needs_revision'])->count(),
            'reviews' => ProductReview::where('is_published',true)->count(),
        ];

        $recent = AiProductAnalysis::with('product')->latest()->take(12)->get();
        return view('admin.ai.dashboard', compact('stats','recent'));
    }
}
