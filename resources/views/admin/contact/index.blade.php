<x-admin-layout>
    <h1 class="text-xl font-bold text-gray-800 mb-6">Contactberichten</h1>

    <x-flash-messages />

    @if($contactMessages->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
            Geen contactberichten gevonden.
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Naam</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">E-mail</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Datum</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($contactMessages as $message)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $message->name }}</td>
                        <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">{{ $message->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $message->sent_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($message->replied_at)
                                <x-status-badge color-classes="bg-emerald-100 text-emerald-700">Beantwoord</x-status-badge>
                            @else
                                <x-status-badge color-classes="bg-amber-100 text-amber-700">Niet beantwoord</x-status-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.contact.show', $message) }}"
                               class="text-grape-500 hover:text-grape-700 text-xs font-semibold">
                                Bekijk →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $contactMessages->links() }}
        </div>
    @endif
</x-admin-layout>
