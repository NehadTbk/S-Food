<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('user', 'orderItems.menuItem', 'deliverer');
        return view('admin.orders.show', compact('order'));
    }

    public function confirm(Order $order): RedirectResponse
    {
        if ($order->status !== 'new') {
            return back()->with('error', 'Alleen nieuwe bestellingen kunnen bevestigd worden.');
        }
        $order->update(['status' => 'confirmed']);
        return back()->with('success', 'Bestelling #' . $order->id . ' bevestigd.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        if ($order->status !== 'new') {
            return back()->with('error', 'Alleen nieuwe bestellingen kunnen geannuleerd worden.');
        }
        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Bestelling #' . $order->id . ' geannuleerd.');
    }

    public function payCash(Order $order): RedirectResponse
    {
        if ($order->status !== 'confirmed' || $order->delivery_type !== 'pickup') {
            return back()->with('error', 'Actie niet mogelijk voor deze bestelling.');
        }
        $order->update(['status' => 'paid', 'paid_at' => now(), 'payment_method' => 'cash']);
        return back()->with('success', 'Bestelling #' . $order->id . ' als betaald (cash) gemarkeerd.');
    }

    public function generateQr(Order $order): RedirectResponse
    {
        if ($order->status !== 'confirmed' || $order->delivery_type !== 'pickup') {
            return back()->with('error', 'Actie niet mogelijk voor deze bestelling.');
        }

        // Generate a unique token if not already set
        if (!$order->payment_token) {
            $order->update(['payment_token' => Str::uuid(), 'payment_method' => 'qr']);
        }

        return back()->with('show_qr', $order->id)->with('qr_token', $order->payment_token);
    }
}
