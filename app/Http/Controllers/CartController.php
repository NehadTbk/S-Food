<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity'     => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $menuItem = MenuItem::findOrFail($request->menu_item_id);

        if (!$menuItem->active) {
            return response()->json(['error' => 'Dit gerecht is niet beschikbaar.'], 422);
        }

        $cart = session('cart', []);
        $id   = $request->menu_item_id;

        $cart[$id] = ($cart[$id] ?? 0) + $request->quantity;

        session(['cart' => $cart]);

        return response()->json([
            'message' => 'Toegevoegd aan winkelmandje.',
            'cart'    => $this->cartSummary(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity'     => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart = session('cart', []);
        $id   = $request->menu_item_id;

        if ($request->quantity === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $request->quantity;
        }

        session(['cart' => $cart]);

        return response()->json(['cart' => $this->cartSummary()]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'menu_item_id' => ['required'],
        ]);

        $cart = session('cart', []);
        unset($cart[$request->menu_item_id]);
        session(['cart' => $cart]);

        return response()->json(['cart' => $this->cartSummary()]);
    }

    public function clear(): JsonResponse
    {
        session()->forget('cart');
        return response()->json(['cart' => $this->cartSummary()]);
    }

    public function summary(): JsonResponse
    {
        return response()->json(['cart' => $this->cartSummary()]);
    }

    public static function cartSummary(): array
    {
        $cart  = session('cart', []);
        $count = array_sum($cart);
        $total = 0;

        if (!empty($cart)) {
            $items = MenuItem::whereIn('id', array_keys($cart))->get();
            foreach ($items as $item) {
                $total += $item->price * ($cart[$item->id] ?? 0);
            }
        }

        return [
            'count' => $count,
            'total' => number_format($total, 2, ',', '.'),
            'items' => $cart,
        ];
    }
}
