<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Betaling – S-Food</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-1">
                <img src="/images/grape-leaf-logo.png" alt="" class="w-8 h-8 object-contain">
                <span class="text-xl font-bold text-black -ml-1">S-Food</span>
            </a>
        </div>

        @if(session('payment_confirmed') || $order->status === 'paid')
        {{-- Already paid / just confirmed --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800 mb-1">Betaling ontvangen!</h1>
            <p class="text-gray-500 text-sm mb-6">
                Bestelling #{{ $order->id }} is betaald.<br>
                Bedankt voor je bestelling bij S-Food.
            </p>
            <div class="bg-gray-50 rounded-xl p-4 text-left text-sm space-y-2 mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-400">Bestelling</span>
                    <span class="font-medium">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Totaal</span>
                    <span class="font-bold">€{{ number_format((float)$order->total, 2, ',', '.') }}</span>
                </div>
            </div>
            <a href="/" class="block w-full bg-grape-500 hover:bg-grape-600 text-white font-semibold py-3 rounded-xl text-sm transition text-center">
                Terug naar S-Food
            </a>
        </div>

        @else
        {{-- Payment form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-xl font-bold text-gray-800 mb-1 text-center">Betaling bevestigen</h1>
            <p class="text-gray-500 text-sm text-center mb-6">Bestelling #{{ $order->id }}</p>

            <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-2 mb-6">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between text-gray-700">
                    <span>{{ $item->quantity }}× {{ $item->menuItem ? $item->menuItem->name : '?' }}</span>
                    <span>€{{ number_format((float)$item->price_at_order * $item->quantity, 2, ',', '.') }}</span>
                </div>
                @endforeach
                @if((float)$order->delivery_cost > 0)
                <div class="flex justify-between text-gray-500 pt-1 border-t border-gray-200">
                    <span>Leveringskost</span>
                    <span>€{{ number_format((float)$order->delivery_cost, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 pt-2 border-t border-gray-200 text-base">
                    <span>Totaal</span>
                    <span>€{{ number_format((float)$order->total, 2, ',', '.') }}</span>
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center mb-6">
                Dit is een demobetaling voor schooldoeleinden.<br>
                Klik op de knop hieronder om de betaling te bevestigen.
            </p>

            <form method="POST" action="{{ route('payment.confirm', $order->payment_token) }}">
                @csrf
                <button type="submit"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl text-base transition shadow-sm">
                    ✓ Bevestig betaling
                </button>
            </form>
        </div>
        @endif
    </div>

</body>
</html>
