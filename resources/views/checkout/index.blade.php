<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Bestelling plaatsen</h2>
    </x-slot>

    {{-- Confirmation modal (auto-opens when order was just placed) --}}
    @if(session('order_placed'))
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

        {{-- Modal --}}
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center z-10" @click.stop>
            {{-- Checkmark --}}
            <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-grape-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-grape-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-1">Bestelling geplaatst!</h2>
            <p class="text-gray-500 text-sm mb-6">Bestelling #{{ session('order_id') }}</p>

            <div class="bg-gray-50 rounded-xl p-4 text-left space-y-2 mb-6 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Datum</span>
                    <span class="font-medium text-gray-800">{{ session('order_date') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tijdslot</span>
                    <span class="font-medium text-gray-800">{{ session('order_time') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Levertype</span>
                    <span class="font-medium text-gray-800">
                        {{ session('order_delivery_type') === 'delivery' ? 'Levering' : 'Afhalen' }}
                    </span>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                    <span class="text-gray-700 font-semibold">Totaal</span>
                    <span class="font-bold text-gray-900">€{{ session('order_total') }}</span>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-6">
                De beheerder bevestigt je bestelling zo snel mogelijk.
                Je ontvangt geen e-mail — bekijk de status via je bestellingen.
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/bestellingen"
                   class="flex-1 bg-grape-500 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-grape-600 transition text-center">
                    Mijn bestellingen
                </a>
                <a href="/"
                   class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 transition text-center">
                    Terug naar menu
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Checkout form --}}
    @if(!session('order_placed'))
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{
             deliveryType: '{{ old('delivery_type', 'pickup') }}',
             subtotal: {{ number_format($subtotal, 2, '.', '') }},
             deliveryCost: 2.50,
             get total() {
                 return this.deliveryType === 'delivery'
                     ? this.subtotal + this.deliveryCost
                     : this.subtotal;
             },
             fmt(val) {
                 return '€\u00a0' + val.toFixed(2).replace('.', ',');
             }
         }">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- Left: Cart summary --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Jouw bestelling</h3>

                    <div class="space-y-3">
                        @foreach($cartItems as $row)
                        <div class="flex items-start gap-3">
                            {{-- Photo --}}
                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                @if($row['item']->photo)
                                    <img src="{{ Storage::url($row['item']->photo) }}"
                                         alt="{{ $row['item']->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $row['item']->name }}</p>
                                <p class="text-xs text-gray-500">{{ $row['quantity'] }} × €{{ number_format((float)$row['item']->price, 2, ',', '.') }}</p>
                            </div>

                            <p class="text-sm font-semibold text-gray-800 flex-shrink-0">
                                €{{ number_format($row['lineTotal'], 2, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 mt-4 pt-4 space-y-1.5 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotaal</span>
                            <span>€{{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600" x-show="deliveryType === 'delivery'">
                            <span>Leveringskost</span>
                            <span>€2,50</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-100 pt-2 mt-2">
                            <span>Totaal</span>
                            <span x-text="fmt(total)"></span>
                        </div>
                    </div>

                    <a href="/" class="mt-4 inline-flex items-center gap-1 text-xs text-grape-500 hover:text-grape-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Bestelling aanpassen
                    </a>
                </div>
            </div>

            {{-- Right: Order form --}}
            <div class="lg:col-span-3">
                <form method="POST" action="{{ route('checkout.store') }}" class="space-y-6">
                    @csrf

                    {{-- Date + Time --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-800 mb-4">Datum & tijdstip</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="chosen_date" class="block text-sm font-medium text-gray-700 mb-1">Datum</label>
                                <input type="date"
                                       id="chosen_date"
                                       name="chosen_date"
                                       value="{{ old('chosen_date', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       max="{{ date('Y-m-d', strtotime('+14 days')) }}"
                                       required
                                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('chosen_date') border-red-400 @enderror">
                                @error('chosen_date')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="chosen_time" class="block text-sm font-medium text-gray-700 mb-1">Tijdslot</label>
                                <select id="chosen_time"
                                        name="chosen_time"
                                        required
                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('chosen_time') border-red-400 @enderror">
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot }}" {{ old('chosen_time') === $slot ? 'selected' : '' }}>
                                            {{ $slot }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chosen_time')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Delivery type --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-800 mb-4">Levertype</h3>

                        <div class="grid grid-cols-2 gap-3">
                            {{-- Pickup --}}
                            <label class="relative flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="deliveryType === 'pickup'
                                       ? 'border-grape-500 bg-grape-50'
                                       : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="delivery_type" value="pickup"
                                       x-model="deliveryType"
                                       class="sr-only">
                                <svg class="w-7 h-7" :class="deliveryType === 'pickup' ? 'text-grape-500' : 'text-gray-400'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>
                                </svg>
                                <span class="text-sm font-semibold" :class="deliveryType === 'pickup' ? 'text-grape-700' : 'text-gray-700'">Afhalen</span>
                                <span class="text-xs text-gray-400">Gratis</span>
                            </label>

                            {{-- Delivery --}}
                            <label class="relative flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="deliveryType === 'delivery'
                                       ? 'border-grape-500 bg-grape-50'
                                       : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="delivery_type" value="delivery"
                                       x-model="deliveryType"
                                       class="sr-only">
                                <svg class="w-7 h-7" :class="deliveryType === 'delivery' ? 'text-grape-500' : 'text-gray-400'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                                <span class="text-sm font-semibold" :class="deliveryType === 'delivery' ? 'text-grape-700' : 'text-gray-700'">Levering</span>
                                <span class="text-xs text-gray-400">+ €2,50</span>
                            </label>
                        </div>

                        @error('delivery_type')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        {{-- Delivery address --}}
                        <div x-show="deliveryType === 'delivery'" x-transition class="mt-5 space-y-3">
                            <p class="text-sm font-medium text-gray-700">Leveradres</p>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <input type="text"
                                           name="street"
                                           placeholder="Straat"
                                           value="{{ old('street', $user->street) }}"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('street') border-red-400 @enderror">
                                    @error('street')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="text"
                                           name="house_number"
                                           placeholder="Nr."
                                           value="{{ old('house_number', $user->house_number) }}"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('house_number') border-red-400 @enderror">
                                    @error('house_number')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <input type="text"
                                       name="bus"
                                       placeholder="Bus (optioneel)"
                                       value="{{ old('bus', $user->bus) }}"
                                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <input type="text"
                                           name="postal_code"
                                           placeholder="Postcode"
                                           value="{{ old('postal_code', $user->postal_code) }}"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('postal_code') border-red-400 @enderror">
                                    @error('postal_code')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-2">
                                    <input type="text"
                                           name="city"
                                           placeholder="Gemeente"
                                           value="{{ old('city', $user->city) }}"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('city') border-red-400 @enderror">
                                    @error('city')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment method --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-800 mb-4">Betaalmethode</h3>

                        <div class="grid grid-cols-2 gap-3" x-data="{ payment: '{{ old('payment_method', 'cash') }}' }">
                            {{-- Cash --}}
                            <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="payment === 'cash'
                                       ? 'border-grape-500 bg-grape-50'
                                       : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="cash"
                                       x-model="payment"
                                       class="sr-only">
                                <svg class="w-6 h-6 flex-shrink-0" :class="payment === 'cash' ? 'text-grape-500' : 'text-gray-400'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm font-semibold" :class="payment === 'cash' ? 'text-grape-700' : 'text-gray-700'">Cash</span>
                            </label>

                            {{-- QR --}}
                            <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="payment === 'qr'
                                       ? 'border-grape-500 bg-grape-50'
                                       : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="payment_method" value="qr"
                                       x-model="payment"
                                       class="sr-only">
                                <svg class="w-6 h-6 flex-shrink-0" :class="payment === 'qr' ? 'text-grape-500' : 'text-gray-400'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                <span class="text-sm font-semibold" :class="payment === 'qr' ? 'text-grape-700' : 'text-gray-700'">QR-code</span>
                            </label>
                        </div>

                        @error('payment_method')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-grape-500 hover:bg-grape-600 text-white font-bold py-3.5 rounded-xl transition text-base shadow-sm">
                        Bestelling bevestigen &nbsp;·&nbsp; <span x-text="fmt(total)"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
