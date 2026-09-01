<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;

class MyFilesController extends Controller
{
    public function index()
    {
        $items = OrderItem::with([
            'product',
            'order'
        ])
        ->whereHas('order', function ($q) {
            $q->where('user_id', auth()->id())
              ->where('status', 'paid');
        })
        ->latest()
        ->paginate(12);

        return view(
            'account.files',
            compact('items')
        );
    }
}