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
                <x-edit-delete-actions
                    :edit-route="route('admin.news.edit', $newsPost)"
                    :delete-route="route('admin.news.destroy', $newsPost)"
                    delete-title="Nieuwtje verwijderen?"
                    :delete-message="'\'' . $newsPost->title . '\' wordt permanent verwijderd.'"
                    icon-class="w-4 h-4"
                    edit-class="text-gray-400 hover:text-grape-500 transition p-1.5"
                    delete-class="text-gray-400 hover:text-red-500 transition p-1.5" />
            </div>
            @endif
        @endauth
    </div>

    <x-flash-messages />

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
