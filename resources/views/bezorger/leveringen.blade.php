@php use Illuminate\Support\Facades\Storage; @endphp
<x-deliverer-layout>

    {{-- QR modal --}}
    @if(session('show_qr') && session('qr_token'))
    @php
        $qrOrderId = session('show_qr');
        $qrUrl     = url('/betalen/' . session('qr_token'));
        $qrImage   = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrUrl);
    @endphp
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-8 text-center z-10" @click.stop>
            <h2 class="text-lg font-bold text-gray-800 mb-1">QR-betaling</h2>
            <p class="text-sm text-gray-500 mb-5">
                Bestelling #{{ $qrOrderId }}<br>
                Laat de klant deze QR-code scannen.
            </p>
            <div class="flex justify-center mb-4">
                <img src="{{ $qrImage }}" alt="QR code" class="w-56 h-56 rounded-lg border border-gray-100">
            </div>
            <a href="{{ $qrUrl }}" target="_blank" class="block text-xs text-grape-500 hover:text-grape-600 underline mb-5 break-all">{{ $qrUrl }}</a>
            <a href="{{ $qrUrl }}" target="_blank"
               class="block w-full bg-grape-500 hover:bg-grape-600 text-white font-semibold py-2.5 rounded-xl text-sm transition mb-2">
                Open betaalpagina
            </a>
            <button @click="open = false"
                    class="w-full border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold py-2.5 rounded-xl text-sm transition">
                Sluiten
            </button>
        </div>
    </div>
    @endif

    <x-flash-messages margin="mb-6" />

    <h1 class="text-xl font-bold text-gray-800 mb-6">Mijn leveringen</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <p class="text-gray-500 font-medium">Je hebt nog geen leveringen aangenomen.</p>
            <a href="{{ route('deliverer.index') }}"
               class="mt-4 inline-block bg-grape-500 hover:bg-grape-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition">
                Bekijk beschikbare leveringen
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach($orders as $order)
            @php
                $inTransit = $order->status === 'in_transit';
            @endphp

            <div class="bg-white rounded-2xl border {{ $inTransit ? 'border-grape-200' : 'border-gray-100' }} shadow-sm p-5">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                    {{-- Left: info --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <p class="text-base font-bold text-gray-800">Bestelling #{{ $order->id }}</p>
                            @if($inTransit)
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Onderweg</span>
                            @else
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Betaald</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Klant</p>
                                <p class="font-medium text-gray-700">{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                                <p class="text-xs text-gray-400">{{ $order->user->phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Datum & tijd</p>
                                <p class="font-medium text-gray-700">{{ $order->chosen_date->format('d/m/Y') }} om {{ $order->chosen_time }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs text-gray-400 mb-0.5">Leveradres</p>
                                <p class="font-medium text-gray-700">
                                    {{ $order->street }} {{ $order->house_number }}{{ $order->bus ? ' bus ' . $order->bus : '' }},
                                    {{ $order->postal_code }} {{ $order->city }}
                                </p>
                            </div>
                        </div>

                        {{-- Order items summary --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500 space-y-0.5">
                            @foreach($order->orderItems as $item)
                                <p>{{ $item->quantity }}× {{ $item->menuItem ? $item->menuItem->name : '(verwijderd)' }}</p>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: total + payment actions --}}
                    <div class="flex flex-col items-end gap-3 flex-shrink-0">
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">€{{ number_format((float)$order->total, 2, ',', '.') }}</p>
                            @if(!$inTransit)
                                <p class="text-xs text-gray-400">{{ $order->payment_method === 'cash' ? 'Cash' : 'QR-code' }}</p>
                            @endif
                        </div>

                        @if($inTransit)
                            <div class="flex gap-2">
                                <x-confirm-form :action="route('deliverer.pay-cash', $order)" method="PATCH"
                                                 title="Cash betaling bevestigen?"
                                                 message="Bestelling #{{ $order->id }} — €{{ number_format((float)$order->total, 2, ',', '.') }} wordt gemarkeerd als betaald."
                                                 confirm-label="Bevestigen"
                                                 class="bg-grape-500 hover:bg-grape-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="2"/>
                                        <circle cx="12" cy="12" r="2.5" stroke-width="2"/>
                                        <path stroke-linecap="round" stroke-width="2" d="M6 9h.01M18 15h.01"/>
                                    </svg>
                                    Cash ontvangen
                                </x-confirm-form>
                                <form method="POST" action="{{ route('deliverer.generate-qr', $order) }}">
                                    @csrf @method('PATCH')
                                    <button class="border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg transition inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                                            <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                                            <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                                            <rect x="14" y="14" width="3" height="3" fill="currentColor" stroke="none"/>
                                            <rect x="18" y="14" width="3" height="3" fill="currentColor" stroke="none"/>
                                            <rect x="14" y="18" width="3" height="3" fill="currentColor" stroke="none"/>
                                            <rect x="18" y="18" width="3" height="3" fill="currentColor" stroke="none"/>
                                        </svg>
                                        QR-code tonen
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-xs text-emerald-600 font-semibold inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Betaald {{ $order->paid_at?->format('d/m H:i') }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</x-deliverer-layout>
