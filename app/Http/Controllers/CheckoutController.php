<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    const DELIVERY_COST = 2.50;

    public function show(): View|RedirectResponse
    {
        if (auth()->user()->role !== 'user') {
            abort(403);
        }

        // If coming back after a successful order, show empty page with modal
        if (session('order_placed')) {
            return view('checkout.index', [
                'cartItems' => [],
                'subtotal'  => 0,
                'timeSlots' => $this->generateTimeSlots(),
                'user'      => auth()->user(),
            ]);
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/')->with('info', 'Je winkelmandje is leeg.');
        }

        $menuItems = MenuItem::whereIn('id', array_keys($cart))->where('active', true)->get();

        // Drop items that were removed or deactivated since they were added to the cart
        $cart = array_intersect_key($cart, array_flip($menuItems->pluck('id')->all()));
        session(['cart' => $cart]);

        if (empty($cart)) {
            return redirect('/')->with('info', 'Eén of meer gerechten in je winkelmandje zijn niet meer beschikbaar. Je winkelmandje is leeggemaakt.');
        }

        $cartItems = [];
        $subtotal  = 0;

        foreach ($menuItems as $item) {
            $qty       = $cart[$item->id] ?? 0;
            $lineTotal = (float) $item->price * $qty;
            $subtotal += $lineTotal;

            $cartItems[] = [
                'item'      => $item,
                'quantity'  => $qty,
                'lineTotal' => $lineTotal,
            ];
        }

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal'  => $subtotal,
            'timeSlots' => $this->generateTimeSlots(),
            'user'      => auth()->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (auth()->user()->role !== 'user') {
            abort(403);
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/')->with('info', 'Je winkelmandje is leeg.');
        }

        $request->validate([
            'chosen_date'    => ['required', 'date', 'after_or_equal:today'],
            'chosen_time'    => ['required', 'string', 'in:' . implode(',', $this->generateTimeSlots())],
            'delivery_type'  => ['required', 'in:pickup,delivery'],
            'payment_method' => ['required', 'in:cash,qr'],
            'street'         => ['required_if:delivery_type,delivery', 'nullable', 'string', 'max:255'],
            'house_number'   => ['required_if:delivery_type,delivery', 'nullable', 'string', 'max:20'],
            'bus'            => ['nullable', 'string', 'max:20'],
            'postal_code'    => ['required_if:delivery_type,delivery', 'nullable', 'string', 'max:10'],
            'city'           => ['required_if:delivery_type,delivery', 'nullable', 'string', 'max:100'],
        ]);

        $menuItems = MenuItem::whereIn('id', array_keys($cart))->where('active', true)->get()->keyBy('id');

        // Drop items that were removed or deactivated since they were added to the cart
        $cart = array_intersect_key($cart, $menuItems->all());

        if (empty($cart)) {
            session()->forget('cart');
            return redirect('/')->with('info', 'Eén of meer gerechten in je winkelmandje zijn niet meer beschikbaar. Je winkelmandje is leeggemaakt.');
        }

        $subtotal     = 0;
        $deliveryCost = $request->delivery_type === 'delivery' ? self::DELIVERY_COST : 0;

        foreach ($cart as $itemId => $qty) {
            if (isset($menuItems[$itemId])) {
                $subtotal += (float) $menuItems[$itemId]->price * $qty;
            }
        }

        $total = $subtotal + $deliveryCost;

        $order = Order::create([
            'user_id'        => auth()->id(),
            'chosen_date'    => $request->chosen_date,
            'chosen_time'    => $request->chosen_time,
            'delivery_type'  => $request->delivery_type,
            'street'         => $request->delivery_type === 'delivery' ? $request->street : null,
            'house_number'   => $request->delivery_type === 'delivery' ? $request->house_number : null,
            'bus'            => $request->delivery_type === 'delivery' ? $request->bus : null,
            'postal_code'    => $request->delivery_type === 'delivery' ? $request->postal_code : null,
            'city'           => $request->delivery_type === 'delivery' ? $request->city : null,
            'status'         => 'new',
            'payment_method' => $request->payment_method,
            'delivery_cost'  => $deliveryCost,
            'total'          => $total,
        ]);

        foreach ($cart as $itemId => $qty) {
            if (isset($menuItems[$itemId])) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'menu_item_id'  => $itemId,
                    'quantity'      => $qty,
                    'price_at_order' => $menuItems[$itemId]->price,
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('checkout.show')
            ->with('order_placed', true)
            ->with('order_id', $order->id)
            ->with('order_date', $order->chosen_date->format('d/m/Y'))
            ->with('order_time', $order->chosen_time)
            ->with('order_delivery_type', $order->delivery_type)
            ->with('order_total', number_format($total, 2, ',', '.'));
    }

    private function generateTimeSlots(): array
    {
        $slots = [];
        $start = strtotime('09:00');
        $end   = strtotime('22:00');

        while ($start <= $end) {
            $slots[] = date('H:i', $start);
            $start  += 30 * 60;
        }

        return $slots;
    }
}
