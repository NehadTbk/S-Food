@php use Illuminate\Support\Facades\Storage; @endphp
<x-admin-layout>
    @php
        $statusMap = [
            'new'        => ['label' => 'Nieuw',       'class' => 'bg-blue-100 text-blue-700'],
            'confirmed'  => ['label' => 'Bevestigd',   'class' => 'bg-grape-100 text-grape-700'],
            'in_transit' => ['label' => 'Onderweg',    'class' => 'bg-amber-100 text-amber-700'],
            'paid'       => ['label' => 'Betaald',     'class' => 'bg-emerald-100 text-emerald-700'],
            'cancelled'  => ['label' => 'Geannuleerd', 'class' => 'bg-red-100 text-red-600'],
        ];
        $status     = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
        $isDelivery = $order->delivery_type === 'delivery';
        $isPickup   = !$isDelivery;
    @endphp

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 bg-grape-50 border border-grape-200 text-grape-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- QR modal (opens if session has show_qr for this order) --}}
    @if(session('show_qr') == $order->id && session('qr_token'))
    @php
        $qrUrl   = url('/betalen/' . session('qr_token'));
        $qrImage = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrUrl);
    @endphp
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-8 text-center z-10" @click.stop>
            <h2 class="text-lg font-bold text-gray-800 mb-1">QR-betaling</h2>
            <p class="text-sm text-gray-500 mb-5">
                Bestelling #{{ $order->id }} — €{{ number_format((float)$order->total, 2, ',', '.') }}<br>
                Laat de klant deze QR-code scannen.
            </p>
            <div class="flex justify-center mb-4">
                <img src="{{ $qrImage }}" alt="QR code" class="w-56 h-56 rounded-lg border border-gray-100">
            </div>
            <p class="text-xs text-gray-400 mb-5 break-all">{{ $qrUrl }}</p>
            <button @click="open = false"
                    class="w-full border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold py-2.5 rounded-xl text-sm transition">
                Sluiten
            </button>
        </div>
    </div>
    @endif

    <div class="max-w-3xl space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Bestelling #{{ $order->id }}</h1>
            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $status['class'] }}">{{ $status['label'] }}</span>
        </div>

        {{-- Action buttons --}}
        @if($order->status === 'new')
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                @csrf @method('PATCH')
                <button class="bg-grape-500 hover:bg-grape-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                    ✓ Bevestig bestelling
                </button>
            </form>
            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                  onsubmit="return confirm('Bestelling annuleren?')">
                @csrf @method('PATCH')
                <button class="border border-red-300 text-red-500 hover:bg-red-50 text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                    ✕ Annuleer bestelling
                </button>
            </form>
        </div>
        @endif

        @if($order->status === 'confirmed' && $isPickup)
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.orders.pay-cash', $order) }}">
                @csrf @method('PATCH')
                <button class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                    💵 Cash ontvangen
                </button>
            </form>
            <form method="POST" action="{{ route('admin.orders.generate-qr', $order) }}">
                @csrf @method('PATCH')
                <button class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                    📱 QR-betaling genereren
                </button>
            </form>
        </div>
        @endif

        @if($order->status === 'confirmed' && $isDelivery)
        <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm">
            Deze bestelling wordt afgehandeld door een bezorger.
        </div>
        @endif

        {{-- Details --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Klant & details</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Klant</p>
                    <p class="font-medium text-gray-800">{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Datum</p>
                    <p class="font-medium text-gray-800">{{ $order->chosen_date->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-400">{{ $order->chosen_time }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Levertype</p>
                    <p class="font-medium text-gray-800">{{ $isDelivery ? 'Levering' : 'Afhalen' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Betaalmethode</p>
                    <p class="font-medium text-gray-800">{{ $order->payment_method === 'cash' ? 'Cash' : 'QR-code' }}</p>
                </div>
                @if($order->paid_at)
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Betaald op</p>
                    <p class="font-medium text-gray-800">{{ $order->paid_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
                @if($order->deliverer)
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Bezorger</p>
                    <p class="font-medium text-gray-800">{{ $order->deliverer->first_name }} {{ $order->deliverer->last_name }}</p>
                </div>
                @endif
            </div>

            @if($isDelivery)
            <div class="mt-4 pt-4 border-t border-gray-100 text-sm">
                <p class="text-gray-400 text-xs mb-1">Leveradres</p>
                <p class="font-medium text-gray-800">
                    {{ $order->street }} {{ $order->house_number }}{{ $order->bus ? ' bus ' . $order->bus : '' }},
                    {{ $order->postal_code }} {{ $order->city }}
                </p>
            </div>
            @endif
        </div>

        {{-- Items --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Gerechten</h3>
            <div class="space-y-3">
                @foreach($order->orderItems as $item)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                        @if($item->menuItem && $item->menuItem->photo)
                            <img src="{{ Storage::url($item->menuItem->photo) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $item->menuItem ? $item->menuItem->name : '(verwijderd)' }}</p>
                        <p class="text-xs text-gray-400">{{ $item->quantity }} × €{{ number_format((float)$item->price_at_order, 2, ',', '.') }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800">
                        €{{ number_format((float)$item->price_at_order * $item->quantity, 2, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>

            <div class="border-t border-gray-100 mt-4 pt-4 space-y-1.5 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotaal</span>
                    <span>€{{ number_format((float)$order->total - (float)$order->delivery_cost, 2, ',', '.') }}</span>
                </div>
                @if($isDelivery)
                <div class="flex justify-between text-gray-500">
                    <span>Leveringskost</span>
                    <span>€{{ number_format((float)$order->delivery_cost, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-100 pt-2">
                    <span>Totaal</span>
                    <span>€{{ number_format((float)$order->total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
