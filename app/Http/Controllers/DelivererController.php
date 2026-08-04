<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DelivererController extends Controller
{
    // Available deliveries: confirmed, delivery type, not yet taken
    public function index(): View
    {
        $orders = Order::with('user')
            ->where('status', 'confirmed')
            ->where('delivery_type', 'delivery')
            ->whereNull('deliverer_id')
            ->latest()
            ->get();

        return view('bezorger.index', compact('orders'));
    }

    // My taken deliveries
    public function myDeliveries(): View
    {
        $orders = Order::with('user', 'orderItems.menuItem')
            ->where('deliverer_id', auth()->id())
            ->whereIn('status', ['in_transit', 'paid'])
            ->latest()
            ->get();

        return view('bezorger.leveringen', compact('orders'));
    }

    // Take a delivery
    public function take(Order $order): RedirectResponse
    {
        $taken = DB::transaction(function () use ($order) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->first();

            if ($locked->status !== 'confirmed'
                || $locked->delivery_type !== 'delivery'
                || $locked->deliverer_id !== null) {
                return false;
            }

            $locked->update([
                'deliverer_id' => auth()->id(),
                'status'       => 'in_transit',
            ]);

            return true;
        });

        if (!$taken) {
            return redirect()->route('deliverer.index')
                ->with('error', 'Deze levering is niet meer beschikbaar.');
        }

        return redirect()->route('deliverer.my-deliveries')
            ->with('success', 'Bestelling #' . $order->id . ' aangenomen.');
    }

    // Mark as paid — cash
    public function payCash(Order $order): RedirectResponse
    {
        if ($order->deliverer_id !== auth()->id() || $order->status !== 'in_transit') {
            return back()->with('error', 'Actie niet mogelijk voor deze bestelling.');
        }

        $order->update(['status' => 'paid', 'paid_at' => now()]);

        return back()->with('success', 'Bestelling #' . $order->id . ' als betaald (cash) gemarkeerd.');
    }

    // Generate QR token and redirect back to show modal
    public function generateQr(Order $order): RedirectResponse
    {
        if ($order->deliverer_id !== auth()->id() || $order->status !== 'in_transit') {
            return back()->with('error', 'Actie niet mogelijk voor deze bestelling.');
        }

        if (!$order->payment_token) {
            $order->update(['payment_token' => Str::uuid()]);
        }

        return back()
            ->with('show_qr', $order->id)
            ->with('qr_token', $order->payment_token);
    }
}
