@php use Illuminate\Support\Facades\Storage; @endphp
<x-admin-layout>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('news.show', $newsPost) }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Nieuwtje bewerken</h1>
        </div>

        <form method="POST" action="{{ route('admin.news.update', $newsPost) }}" enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf @method('PATCH')

            <x-form-field label="Titel" name="title" required>
                <input type="text" name="title" value="{{ old('title', $newsPost->title) }}" required maxlength="255"
                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('title') border-red-400 @enderror">
            </x-form-field>

            <x-form-field label="Publicatiedatum" name="publication_date" required>
                <input type="date" name="publication_date"
                       value="{{ old('publication_date', $newsPost->publication_date->format('Y-m-d')) }}" required
                       class="rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('publication_date') border-red-400 @enderror">
            </x-form-field>

            <x-form-field label="Inhoud" name="content" required>
                <textarea name="content" rows="6" required
                          class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('content') border-red-400 @enderror">{{ old('content', $newsPost->content) }}</textarea>
            </x-form-field>

            <x-form-field label="Afbeelding" name="image">
                @if($newsPost->image)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ Storage::url($newsPost->image) }}" alt="Huidige afbeelding"
                         class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    <span class="text-xs text-gray-400">Huidige afbeelding — upload een nieuwe om te vervangen</span>
                </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-grape-50 file:text-grape-700 hover:file:bg-grape-100">
                <p class="mt-1 text-xs text-gray-400">JPG of PNG, max. 10 MB. Laat leeg om de huidige afbeelding te behouden.</p>
            </x-form-field>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                    Wijzigingen opslaan
                </button>
                <a href="{{ route('news.show', $newsPost) }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                    Annuleer
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
