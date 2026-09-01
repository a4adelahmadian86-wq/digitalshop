<?php

namespace App\Http\Controllers;

use App\Models\Order;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        abort_unless(
            $order->status === 'paid',
            404
        );

        $order->load([
            'items.product',
        ]);

        return view(
            'account.invoice',
            compact('order')
        );
    }
}