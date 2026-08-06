@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-grape-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Terug naar nieuws
        </a>
        @auth
            @if(auth()->user()->role === 'admin')
            <div class="flex gap-1">
                <a href="{{ route('admin.news.edit', $newsPost) }}"
                   class="text-gray-400 hover:text-grape-500 transition p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <x-confirm-form :action="route('admin.news.destroy', $newsPost)" method="DELETE"
                                 title="Nieuwtje verwijderen?"
                                 message="'{{ $newsPost->title }}' wordt permanent verwijderd."
                                 confirm-label="Verwijderen" confirm-class="bg-red-500 hover:bg-red-600"
                                 class="text-gray-400 hover:text-red-500 transition p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </x-confirm-form>
            </div>
            @endif
        @endauth
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($newsPost->image)
            <img src="{{ Storage::url($newsPost->image) }}" alt="{{ $newsPost->title }}" class="w-full h-64 sm:h-80 object-cover">
        @endif

        <div class="p-6 sm:p-8">
            <p class="text-xs text-gray-400 mb-2">{{ $newsPost->publication_date->format('d/m/Y') }}</p>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $newsPost->title }}</h1>
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $newsPost->content }}</div>
        </div>
    </div>

</div>
</x-app-layout>
