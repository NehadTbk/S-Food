@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nieuws</h1>
        @auth
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.news.create') }}"
               class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                + Nieuwtje toevoegen
            </a>
            @endif
        @endauth
    </div>

    <x-flash-messages />

    @if($newsPosts->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6v-3H7v3z"/>
            </svg>
            <p class="text-sm">Nog geen nieuwtjes beschikbaar.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($newsPosts as $post)
            <div class="relative group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                @auth
                    @if(auth()->user()->role === 'admin')
                    <div class="absolute top-2 right-2 z-10 flex gap-1 opacity-0 group-hover:opacity-100 transition">
                        <x-edit-delete-actions
                            :edit-route="route('admin.news.edit', $post)"
                            :delete-route="route('admin.news.destroy', $post)"
                            delete-title="Nieuwtje verwijderen?"
                            :delete-message="'\'' . $post->title . '\' wordt permanent verwijderd.'"
                            icon-class="w-3.5 h-3.5"
                            edit-class="bg-white/90 backdrop-blur text-gray-500 hover:text-grape-500 rounded-lg p-1.5 shadow-sm"
                            delete-class="bg-white/90 backdrop-blur text-gray-500 hover:text-red-500 rounded-lg p-1.5 shadow-sm" />
                    </div>
                    @endif
                @endauth
                <a href="{{ route('news.show', $post) }}" class="block">
                    <div class="w-full h-40 bg-gray-100">
                        @if($post->image)
                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 mb-1">{{ $post->publication_date->format('d/m/Y') }}</p>
                        <h2 class="font-bold text-gray-800 mb-1">{{ $post->title }}</h2>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $newsPosts->links() }}
        </div>
    @endif

</div>
</x-app-layout>
