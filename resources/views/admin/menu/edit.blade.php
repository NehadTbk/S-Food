@php use Illuminate\Support\Facades\Storage; @endphp
<x-admin-layout>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Gerecht bewerken</h1>
        </div>

        <form method="POST" action="{{ route('admin.menu.update', $menuItem) }}" enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5"
              x-data="{ type: '{{ old('type', $menuItem->type) }}' }">
            @csrf @method('PATCH')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naam <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $menuItem->name) }}" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categorie <span class="text-red-400">*</span></label>
                    <select name="category_id" required
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('category_id') border-red-400 @enderror">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prijs (per persoon) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                        <input type="number" name="price" value="{{ old('price', $menuItem->price) }}" step="0.01" min="0" required
                               class="w-full pl-7 rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('price') border-red-400 @enderror">
                    </div>
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Korte beschrijving <span class="text-red-400">*</span></label>
                    <input type="text" name="short_description" value="{{ old('short_description', $menuItem->short_description) }}" required maxlength="255"
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('short_description') border-red-400 @enderror">
                    @error('short_description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Volledige beschrijving</label>
                    <textarea name="full_description" rows="3"
                              class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">{{ old('full_description', $menuItem->full_description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ingrediënten</label>
                    <textarea name="ingredients" rows="2"
                              class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">{{ old('ingredients', $menuItem->ingredients) }}</textarea>
                </div>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-400">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="fixed" x-model="type"
                               class="text-grape-500 focus:ring-grape-500">
                        <span class="text-sm text-gray-700">Vast gerecht</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="daily_special" x-model="type"
                               class="text-grape-500 focus:ring-grape-500">
                        <span class="text-sm text-gray-700">Dagspecial</span>
                    </label>
                </div>
                <div x-show="type === 'daily_special'" x-transition class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Beschikbaar op <span class="text-red-400">*</span></label>
                    <input type="date" name="available_on"
                           value="{{ old('available_on', $menuItem->available_on?->format('Y-m-d')) }}"
                           class="rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('available_on') border-red-400 @enderror">
                    @error('available_on') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Allergeens --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Allergenen</label>
                <div class="flex flex-wrap gap-2">
                    @php $selectedAllergeens = old('allergeens', $menuItem->allergeens->pluck('id')->toArray()); @endphp
                    @foreach($allergeens as $al)
                    <label class="flex items-center gap-1.5 cursor-pointer text-sm">
                        <input type="checkbox" name="allergeens[]" value="{{ $al->id }}"
                               {{ in_array($al->id, $selectedAllergeens) ? 'checked' : '' }}
                               class="rounded text-grape-500 focus:ring-grape-500">
                        {{ $al->name }}
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Photo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                @if($menuItem->photo)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ Storage::url($menuItem->photo) }}" alt="Huidige foto"
                         class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    <span class="text-xs text-gray-400">Huidige foto — upload een nieuwe om te vervangen</span>
                </div>
                @endif
                <input type="file" name="photo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-grape-50 file:text-grape-700 hover:file:bg-grape-100">
                <p class="mt-1 text-xs text-gray-400">JPG of PNG, max. 10 MB. Laat leeg om de huidige foto te behouden.</p>
                @error('photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Active --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" id="active"
                       {{ old('active', $menuItem->active) ? 'checked' : '' }}
                       class="rounded text-grape-500 focus:ring-grape-500">
                <label for="active" class="text-sm text-gray-700">Actief (zichtbaar op de site)</label>
            </div>

            {{-- Vegan --}}
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_vegan" value="0">
                <input type="checkbox" name="is_vegan" value="1" id="is_vegan"
                       {{ old('is_vegan', $menuItem->is_vegan) ? 'checked' : '' }}
                       class="rounded text-grape-500 focus:ring-grape-500">
                <label for="is_vegan" class="text-sm text-gray-700">Vegan</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                    Wijzigingen opslaan
                </button>
                <a href="{{ route('admin.dashboard') }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                    Annuleer
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
