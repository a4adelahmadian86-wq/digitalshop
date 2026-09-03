<?php

namespace App\Http\Controllers;

use App\Models\AiFeedback;
use Illuminate\View\View;

class AdminAiFeedbackController extends Controller
{
    public function index(): View
    {
        $feedback = AiFeedback::with(['user','product'])->latest()->paginate(30);
        $stats = [
            'total' => AiFeedback::count(),
            'positive' => AiFeedback::where('rating','>=',4)->count(),
            'negative' => AiFeedback::where('rating','<=',2)->count(),
            'unresolved' => AiFeedback::whereNull('resolved_at')->count(),
        ];
        return view('admin.ai.feedback', compact('feedback','stats'));
    }
}
