<x-deliverer-layout>

    <x-flash-messages margin="mb-6" />

    <h1 class="text-xl font-bold text-gray-800 mb-6">Beschikbare leveringen</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <svg class="mx-auto w-14 h-14 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
            </svg>
            <p class="text-gray-500 font-medium">Geen leveringen beschikbaar op dit moment.</p>
            <p class="text-sm text-gray-400 mt-1">Vernieuw de pagina om te controleren of er nieuwe leveringen zijn.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($orders as $order)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col">

                {{-- Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-base font-bold text-gray-800">Bestelling #{{ $order->id }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $order->chosen_date->format('d/m/Y') }} om {{ $order->chosen_time }}
                        </p>
                    </div>
                    <span class="text-sm font-bold text-gray-900">
                        €{{ number_format((float)$order->total, 2, ',', '.') }}
                    </span>
                </div>

                {{-- Delivery address --}}
                <div class="bg-gray-50 rounded-xl p-3 mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Leveradres</p>
                    <p class="text-sm text-gray-800 font-medium">
                        {{ $order->street }} {{ $order->house_number }}{{ $order->bus ? ' bus ' . $order->bus : '' }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $order->postal_code }} {{ $order->city }}</p>
                </div>

                {{-- Customer --}}
                <div class="flex justify-between text-xs text-gray-400 mb-5">
                    <span>{{ $order->user->first_name }} {{ $order->user->last_name }}</span>
                </div>

                {{-- Take button --}}
                <form method="POST" action="{{ route('deliverer.take', $order) }}" class="mt-auto">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full bg-grape-500 hover:bg-grape-600 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                        Levering aannemen
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    @endif

</x-deliverer-layout>
