<x-admin-layout>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.contact.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Contactbericht</h1>
        </div>

        <x-flash-messages />

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm">
                <p><span class="text-gray-400">Naam:</span> <span class="font-medium text-gray-800">{{ $contactMessage->name }}</span></p>
                <p><span class="text-gray-400">E-mail:</span> <span class="font-medium text-gray-800">{{ $contactMessage->email }}</span></p>
                <p><span class="text-gray-400">Verstuurd:</span> <span class="font-medium text-gray-800">{{ $contactMessage->sent_at->format('d/m/Y H:i') }}</span></p>
            </div>
            <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line border-t border-gray-100 pt-4">
                {{ $contactMessage->message }}
            </div>
        </div>

        @if($contactMessage->replied_at)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 mt-4">
            <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-2">
                Beantwoord op {{ $contactMessage->replied_at->format('d/m/Y H:i') }}
            </p>
            <p class="text-sm text-emerald-800 leading-relaxed whitespace-pre-line">{{ $contactMessage->reply }}</p>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mt-4">
            <h2 class="text-sm font-bold text-gray-800 mb-3">{{ $contactMessage->replied_at ? 'Opnieuw antwoorden' : 'Antwoorden' }}</h2>
            <form method="POST" action="{{ route('admin.contact.reply', $contactMessage) }}">
                @csrf
                <textarea name="reply" rows="5" required
                          class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('reply') border-red-400 @enderror"
                          placeholder="Typ je antwoord...">{{ old('reply') }}</textarea>
                @error('reply') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                <div class="flex justify-end mt-4">
                    <button type="submit"
                            class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                        Antwoord versturen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
