<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        if (auth()->user()->role !== 'user') {
            abort(403);
        }

        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        if (auth()->user()->role !== 'user' || $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.menuItem');

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        if (auth()->user()->role !== 'user' || $order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'new') {
            return back()->with('error', 'Alleen bestellingen met status "Nieuw" kunnen geannuleerd worden.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')
            ->with('success', 'Bestelling #' . $order->id . ' is geannuleerd.');
    }
}
