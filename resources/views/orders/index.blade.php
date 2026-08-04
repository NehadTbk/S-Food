<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Mijn bestellingen</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 bg-grape-50 border border-grape-200 text-grape-700 rounded-xl px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="text-center py-20">
                <svg class="mx-auto w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-500 font-medium">Je hebt nog geen bestellingen geplaatst.</p>
                <a href="/" class="mt-4 inline-block bg-grape-500 hover:bg-grape-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition">
                    Bekijk het menu
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                @php
                    $statusMap = [
                        'new'        => ['label' => 'Nieuw',       'class' => 'bg-blue-100 text-blue-700'],
                        'confirmed'  => ['label' => 'Bevestigd',   'class' => 'bg-grape-100 text-grape-700'],
                        'in_transit' => ['label' => 'Onderweg',    'class' => 'bg-amber-100 text-amber-700'],
                        'paid'       => ['label' => 'Betaald',     'class' => 'bg-emerald-100 text-emerald-700'],
                        'cancelled'  => ['label' => 'Geannuleerd', 'class' => 'bg-red-100 text-red-600'],
                    ];
                    $status = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
                @endphp

                <a href="{{ route('orders.show', $order) }}"
                   class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-grape-200 transition p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-base font-bold text-gray-800">Bestelling #{{ $order->id }}</span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </div>

                            <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                                <span>{{ $order->chosen_date->format('d/m/Y') }} om {{ $order->chosen_time }}</span>
                                <span>{{ $order->delivery_type === 'delivery' ? 'Levering' : 'Afhalen' }}</span>
                                @if($order->payment_method)
                                    <span>{{ $order->payment_method === 'cash' ? 'Cash' : 'QR-code' }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="text-base font-bold text-gray-900">
                                €{{ number_format((float)$order->total, 2, ',', '.') }}
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
