<x-admin-layout>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-xl font-bold text-gray-800">Bestellingen</h1>

        <form method="GET" action="{{ route('admin.orders.index') }}">
            <select name="status" onchange="this.form.submit()"
                    class="text-sm rounded-lg border-gray-300 py-1.5 focus:ring-grape-500 focus:border-grape-500">
                <option value="">Alle statussen</option>
                <option value="new"        {{ request('status') === 'new'        ? 'selected' : '' }}>Nieuw</option>
                <option value="confirmed"  {{ request('status') === 'confirmed'  ? 'selected' : '' }}>Bevestigd</option>
                <option value="in_transit" {{ request('status') === 'in_transit' ? 'selected' : '' }}>Onderweg</option>
                <option value="paid"       {{ request('status') === 'paid'       ? 'selected' : '' }}>Betaald</option>
                <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Geannuleerd</option>
            </select>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-grape-50 border border-grape-200 text-grape-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    @php
        $statusMap = [
            'new'        => ['label' => 'Nieuw',       'class' => 'bg-blue-100 text-blue-700'],
            'confirmed'  => ['label' => 'Bevestigd',   'class' => 'bg-grape-100 text-grape-700'],
            'in_transit' => ['label' => 'Onderweg',    'class' => 'bg-amber-100 text-amber-700'],
            'paid'       => ['label' => 'Betaald',     'class' => 'bg-emerald-100 text-emerald-700'],
            'cancelled'  => ['label' => 'Geannuleerd', 'class' => 'bg-red-100 text-red-600'],
        ];
    @endphp

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
            Geen bestellingen gevonden.
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Klant</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Datum</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Type</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Totaal</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
                    @php $status = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600']; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">
                            {{ $order->user->first_name }} {{ $order->user->last_name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $order->chosen_date->format('d/m/Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $order->chosen_time }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 hidden md:table-cell">
                            {{ $order->delivery_type === 'delivery' ? 'Levering' : 'Afhalen' }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                            €{{ number_format((float)$order->total, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-grape-500 hover:text-grape-700 text-xs font-semibold">
                                Bekijk →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-admin-layout>
