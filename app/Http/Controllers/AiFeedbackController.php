<?php

namespace App\Http\Controllers;

use App\Models\AiFeedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiFeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'rating' => ['required','integer','between:1,5'],
            'type' => ['nullable','string','max:40'],
            'comment' => ['nullable','string','max:3000'],
            'message_id' => ['nullable','string','max:100'],
            'product_id' => ['nullable','integer','exists:products,id'],
            'context' => ['nullable','array'],
        ]);

        AiFeedback::create([
            'user_id' => auth()->id(),
            'session_id' => Str::limit((string) $request->session()->getId(), 100, ''),
            ...$data,
        ]);

        return response()->json(['ok' => true, 'message' => 'بازخورد شما ثبت شد و برای بهبود پاسخ‌های دستیار استفاده می‌شود.']);
    }
}
