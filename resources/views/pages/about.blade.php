@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Over ons</h1>
        @auth
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.pages.about.edit') }}"
               class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                Bewerken
            </a>
            @endif
        @endauth
    </div>

    <x-flash-messages margin="mb-6" />

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($aboutPage->image)
            <img src="{{ Storage::url($aboutPage->image) }}" alt="Over ons" class="w-full h-56 sm:h-72 object-cover">
        @else
            <div class="w-full h-56 sm:h-72 bg-gray-100 flex items-center justify-center text-gray-300">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <div class="p-6 sm:p-8 space-y-4 text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $aboutPage->content }}</div>
    </div>

</div>
</x-app-layout>
