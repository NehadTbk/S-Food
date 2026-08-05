@php use Illuminate\Support\Facades\Storage; @endphp
<x-admin-layout>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-6 bg-grape-50 border border-grape-200 text-grape-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Left: Categories --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-base font-bold text-gray-800 mb-4">Categorieën</h2>

                <ul class="space-y-1 mb-4">
                    @foreach($categories as $cat)
                    <li x-data="{ editing: false }" class="group">
                        <div x-show="!editing" class="flex items-center justify-between py-1">
                            <span class="text-sm text-gray-700">{{ $cat->name }}
                                <span class="text-xs text-gray-400">({{ $cat->menu_items_count }})</span>
                            </span>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                <button @click="editing = true" class="text-gray-400 hover:text-grape-500 p-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <x-confirm-form :action="route('admin.categories.destroy', $cat)" method="DELETE"
                                                 title="Categorie verwijderen?"
                                                 message="'{{ $cat->name }}' wordt permanent verwijderd."
                                                 confirm-label="Verwijderen" confirm-class="bg-red-500 hover:bg-red-600"
                                                 class="text-gray-400 hover:text-red-500 p-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </x-confirm-form>
                            </div>
                        </div>
                        <form x-show="editing" method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex gap-1 py-1">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $cat->name }}"
                                   class="flex-1 text-sm rounded-lg border-gray-300 py-1 px-2 focus:ring-grape-500 focus:border-grape-500">
                            <button type="submit" class="text-grape-500 hover:text-grape-700 text-xs font-semibold px-1">OK</button>
                            <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-600 text-xs px-1">✕</button>
                        </form>
                    </li>
                    @endforeach
                </ul>

                {{-- Add category --}}
                <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2 mt-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nieuwe categorie"
                           class="flex-1 text-sm rounded-lg border-gray-300 py-1.5 px-2 focus:ring-grape-500 focus:border-grape-500">
                    <button type="submit"
                            class="bg-grape-500 hover:bg-grape-600 text-white text-sm px-3 py-1.5 rounded-lg transition">+</button>
                </form>
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Right: Menu items --}}
        <div class="lg:col-span-3">
            {{-- Header + filters --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Menukaart</h2>
                <div class="flex flex-wrap gap-2 items-center">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-2">
                        <select name="category" onchange="this.form.submit()"
                                class="text-sm rounded-lg border-gray-300 py-1.5 focus:ring-grape-500 focus:border-grape-500">
                            <option value="">Alle categorieën</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="type" onchange="this.form.submit()"
                                class="text-sm rounded-lg border-gray-300 py-1.5 focus:ring-grape-500 focus:border-grape-500">
                            <option value="">Alle types</option>
                            <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Vast</option>
                            <option value="daily_special" {{ request('type') === 'daily_special' ? 'selected' : '' }}>Dagspecial</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.menu.create') }}"
                       class="bg-grape-500 hover:bg-grape-600 text-white text-sm font-semibold px-4 py-1.5 rounded-lg transition">
                        + Nieuw gerecht
                    </a>
                </div>
            </div>

            @if($menuItems->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
                    Nog geen gerechten. Voeg het eerste gerecht toe.
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Gerecht</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Categorie</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Type</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Prijs</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actief</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($menuItems as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                            @if($item->photo)
                                                <img src="{{ Storage::url($item->photo) }}" class="w-full h-full object-cover" alt="{{ $item->name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 flex items-center gap-1.5">
                                                {{ $item->name }}
                                                @if($item->is_vegan)
                                                    <img src="/images/vegan-logo.jpg" alt="Vegan" title="Vegan" class="w-4 h-4 rounded-full">
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ $item->short_description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">{{ $item->category->name }}</td>
                                <td class="px-4 py-3 hidden md:table-cell">
                                    @if($item->type === 'daily_special')
                                        <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">
                                            Dagspecial {{ $item->available_on ? $item->available_on->format('d/m') : '' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Vast</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-800">
                                    €{{ number_format((float)$item->price, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form method="POST" action="{{ route('admin.menu.toggle', $item) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="w-10 h-6 rounded-full transition-colors {{ $item->active ? 'bg-grape-500' : 'bg-gray-200' }} relative flex-shrink-0"
                                                title="{{ $item->active ? 'Deactiveren' : 'Activeren' }}">
                                            <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform {{ $item->active ? 'translate-x-4' : '' }}"></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('admin.menu.edit', $item) }}"
                                           class="text-gray-400 hover:text-grape-500 transition p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <x-confirm-form :action="route('admin.menu.destroy', $item)" method="DELETE"
                                                         title="Gerecht verwijderen?"
                                                         message="'{{ $item->name }}' wordt permanent verwijderd."
                                                         confirm-label="Verwijderen" confirm-class="bg-red-500 hover:bg-red-600"
                                                         class="text-gray-400 hover:text-red-500 transition p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </x-confirm-form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
