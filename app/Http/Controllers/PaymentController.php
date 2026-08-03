<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(string $token): View
    {
        $order = Order::where('payment_token', $token)->firstOrFail();
        $order->load('orderItems.menuItem');
        return view('payment.demo', compact('order'));
    }

    public function confirm(string $token): RedirectResponse
    {
        $order = Order::where('payment_token', $token)->firstOrFail();

        if ($order->status === 'paid') {
            return redirect()->route('payment.show', $token)
                ->with('already_paid', true);
        }

        $order->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('payment.show', $token)
            ->with('payment_confirmed', true);
    }
}
