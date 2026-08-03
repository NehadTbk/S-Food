<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('orders.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-gray-800">Bestelling #{{ $order->id }}</h2>
        </div>
    </x-slot>

    @php
        $statusMap = [
            'new'        => ['label' => 'Nieuw',       'class' => 'bg-blue-100 text-blue-700',       'step' => 1],
            'confirmed'  => ['label' => 'Bevestigd',   'class' => 'bg-grape-100 text-grape-700',     'step' => 2],
            'in_transit' => ['label' => 'Onderweg',    'class' => 'bg-amber-100 text-amber-700',     'step' => 3],
            'paid'       => ['label' => 'Betaald',     'class' => 'bg-emerald-100 text-emerald-700', 'step' => 4],
            'cancelled'  => ['label' => 'Geannuleerd', 'class' => 'bg-red-100 text-red-600',         'step' => 0],
        ];
        $status      = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600', 'step' => 0];
        $currentStep = $status['step'];
        $isDelivery  = $order->delivery_type === 'delivery';
        $steps = $isDelivery
            ? [1 => 'Nieuw', 2 => 'Bevestigd', 3 => 'Onderweg', 4 => 'Betaald']
            : [1 => 'Nieuw', 2 => 'Bevestigd', 4 => 'Betaald'];
    @endphp

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Status card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Status</h3>
                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $status['class'] }}">
                    {{ $status['label'] }}
                </span>
            </div>

            @if($order->status !== 'cancelled')
            {{-- Progress steps --}}
            <div class="flex items-center">
                @foreach($steps as $step => $label)
                    @php $done = $currentStep >= $step; @endphp
                    <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
                        {{-- Circle --}}
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                {{ $done ? 'bg-grape-500' : 'bg-gray-100' }} transition">
                                @if($done)
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                @endif
                            </div>
                            <span class="mt-1.5 text-[11px] text-center whitespace-nowrap
                                {{ $done ? 'text-grape-600 font-semibold' : 'text-gray-400' }}">
                                {{ $label }}
                            </span>
                        </div>
                        {{-- Connector line --}}
                        @if(!$loop->last)
                            <div class="flex-1 h-0.5 mx-1 {{ $currentStep > $step ? 'bg-grape-400' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-red-500">Deze bestelling werd geannuleerd.</p>
            @endif
        </div>

        {{-- Order details --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Details</h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Datum</p>
                    <p class="font-medium text-gray-800">{{ $order->chosen_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Tijdslot</p>
                    <p class="font-medium text-gray-800">{{ $order->chosen_time }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Levertype</p>
                    <p class="font-medium text-gray-800">{{ $isDelivery ? 'Levering' : 'Afhalen' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-0.5">Betaling</p>
                    <p class="font-medium text-gray-800">{{ $order->payment_method === 'cash' ? 'Cash' : 'QR-code' }}</p>
                </div>
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

        {{-- Order items --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Gerechten</h3>

            <div class="space-y-3">
                @foreach($order->orderItems as $item)
                <div class="flex items-center gap-4">
                    {{-- Photo --}}
                    <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                        @if($item->menuItem && $item->menuItem->photo)
                            <img src="{{ Storage::url($item->menuItem->photo) }}"
                                 alt="{{ $item->menuItem->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $item->menuItem ? $item->menuItem->name : '(gerecht verwijderd)' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $item->quantity }} persoon{{ $item->quantity > 1 ? 'en' : '' }}
                            × €{{ number_format((float)$item->price_at_order, 2, ',', '.') }}
                        </p>
                    </div>

                    <p class="text-sm font-semibold text-gray-800 flex-shrink-0">
                        €{{ number_format((float)$item->price_at_order * $item->quantity, 2, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="border-t border-gray-100 mt-5 pt-4 space-y-1.5 text-sm">
                @php
                    $subtotal = (float)$order->total - (float)$order->delivery_cost;
                @endphp
                <div class="flex justify-between text-gray-500">
                    <span>Subtotaal</span>
                    <span>€{{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>
                @if($isDelivery)
                <div class="flex justify-between text-gray-500">
                    <span>Leveringskost</span>
                    <span>€{{ number_format((float)$order->delivery_cost, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-100 pt-2 mt-1">
                    <span>Totaal</span>
                    <span>€{{ number_format((float)$order->total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Cancel button --}}
        @if($order->status === 'new')
        <div x-data="{ confirm: false }" class="bg-white rounded-2xl border border-red-100 shadow-sm p-6">
            <div x-show="!confirm">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Bestelling annuleren</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Je kunt deze bestelling annuleren zolang ze nog niet bevestigd is.
                </p>
                <button @click="confirm = true"
                        class="text-sm text-red-500 hover:text-red-700 font-semibold underline transition">
                    Bestelling annuleren
                </button>
            </div>

            <div x-show="confirm" x-transition>
                <p class="text-sm font-semibold text-gray-800 mb-4">
                    Ben je zeker dat je bestelling #{{ $order->id }} wil annuleren?
                </p>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('orders.cancel', $order) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                            Ja, annuleer
                        </button>
                    </form>
                    <button @click="confirm = false"
                            class="border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition">
                        Terug
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
