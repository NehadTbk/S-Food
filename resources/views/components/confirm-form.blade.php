@props([
    'action',
    'method' => 'POST',
    'title',
    'message',
    'confirmLabel' => 'Bevestigen',
    'confirmClass' => 'bg-grape-500 hover:bg-grape-600',
])

<form method="POST" action="{{ $action }}" x-data="{ open: false }">
    @csrf
    @if(strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <button type="button" @click="open = true" {{ $attributes }}>
        {{ $slot }}
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 z-10" @click.stop>
            <h3 class="text-base font-bold text-gray-800 mb-1">{{ $title }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>
            <div class="flex gap-3">
                <button type="button" @click="open = false"
                        class="flex-1 border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold py-2.5 rounded-xl text-sm transition">
                    Annuleren
                </button>
                <button type="submit"
                        class="flex-1 {{ $confirmClass }} text-white font-semibold py-2.5 rounded-xl text-sm transition">
                    {{ $confirmLabel }}
                </button>
            </div>
        </div>
    </div>
</form>
