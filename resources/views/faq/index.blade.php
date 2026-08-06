<x-app-layout>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Veelgestelde vragen</h1>
        @auth
            @if(auth()->user()->role === 'admin')
            <div x-data="{ adding: false }">
                <button type="button" @click="adding = true" x-show="!adding"
                        class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    + Categorie toevoegen
                </button>
                <form x-show="adding" x-cloak method="POST" action="{{ route('admin.faq.categories.store') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Naam categorie" required maxlength="100"
                           class="text-sm rounded-lg border-gray-300 focus:ring-grape-500 focus:border-grape-500">
                    <button type="submit" class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-3 py-2 rounded-lg text-xs transition">OK</button>
                    <button type="button" @click="adding = false" class="text-gray-400 hover:text-gray-600 text-xs px-2">✕</button>
                </form>
            </div>
            @endif
        @endauth
    </div>

    @if($faqCategories->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm">Nog geen vragen beschikbaar.</p>
        </div>
    @else
        <div class="space-y-8">
            @foreach($faqCategories as $category)
            <section>
                <div x-data="{ editing: false }" class="group mb-3">
                    <div x-show="!editing" class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-grape-500 uppercase tracking-wide">{{ $category->name }}</h2>
                        @auth
                            @if(auth()->user()->role === 'admin')
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                <button @click="editing = true" class="text-gray-400 hover:text-grape-500 p-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                @if($category->faqItems->isEmpty())
                                <x-confirm-form :action="route('admin.faq.categories.destroy', $category)" method="DELETE"
                                                 title="Categorie verwijderen?"
                                                 message="'{{ $category->name }}' wordt permanent verwijderd."
                                                 confirm-label="Verwijderen" confirm-class="bg-red-500 hover:bg-red-600"
                                                 class="text-gray-400 hover:text-red-500 p-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </x-confirm-form>
                                @else
                                <span class="text-gray-200 p-0.5 cursor-not-allowed" title="Verwijder eerst alle vragen in deze categorie">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </span>
                                @endif
                            </div>
                            @endif
                        @endauth
                    </div>
                    @auth
                        @if(auth()->user()->role === 'admin')
                        <form x-show="editing" x-cloak method="POST" action="{{ route('admin.faq.categories.update', $category) }}" class="flex gap-1">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $category->name }}" maxlength="100"
                                   class="flex-1 text-sm rounded-lg border-gray-300 py-1 px-2 focus:ring-grape-500 focus:border-grape-500">
                            <button type="submit" class="text-grape-500 hover:text-grape-700 text-xs font-semibold px-1">OK</button>
                            <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-600 text-xs px-1">✕</button>
                        </form>
                        @endif
                    @endauth
                </div>

                @if($category->faqItems->isEmpty())
                    <p class="text-sm text-gray-400">Nog geen vragen in deze categorie.</p>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100 overflow-hidden">
                        @foreach($category->faqItems as $item)
                        <div x-data="{ open: false, editing: false }">
                            <div class="flex items-center justify-between gap-2 px-5 py-4">
                                <button @click="open = !open" x-show="!editing"
                                        class="flex-1 flex items-center justify-between gap-4 text-left">
                                    <span class="text-sm font-medium text-gray-800">{{ $item->question }}</span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                                         :class="{ 'rotate-180': open }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                @auth
                                    @if(auth()->user()->role === 'admin')
                                    <div class="flex gap-1 flex-shrink-0" x-show="!editing">
                                        <button type="button" @click="editing = true; open = false" class="text-gray-400 hover:text-grape-500 p-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <x-confirm-form :action="route('admin.faq.items.destroy', $item)" method="DELETE"
                                                         title="Vraag verwijderen?"
                                                         message="'{{ $item->question }}' wordt permanent verwijderd."
                                                         confirm-label="Verwijderen" confirm-class="bg-red-500 hover:bg-red-600"
                                                         class="text-gray-400 hover:text-red-500 p-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </x-confirm-form>
                                    </div>
                                    @endif
                                @endauth
                            </div>
                            <div x-show="open && !editing" x-cloak class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">
                                {{ $item->answer }}
                            </div>
                            @auth
                                @if(auth()->user()->role === 'admin')
                                <div x-show="editing" x-cloak class="px-5 pb-4">
                                    <form method="POST" action="{{ route('admin.faq.items.update', $item) }}" class="space-y-2">
                                        @csrf @method('PATCH')
                                        <input type="text" name="question" value="{{ $item->question }}" required maxlength="255"
                                               class="w-full text-sm rounded-lg border-gray-300 py-1.5 px-2 focus:ring-grape-500 focus:border-grape-500">
                                        <textarea name="answer" rows="2" required
                                                  class="w-full text-sm rounded-lg border-gray-300 py-1.5 px-2 focus:ring-grape-500 focus:border-grape-500">{{ $item->answer }}</textarea>
                                        <div class="flex gap-2">
                                            <button type="submit" class="text-grape-500 hover:text-grape-700 text-xs font-semibold px-1">Opslaan</button>
                                            <button type="button" @click="editing = false" class="text-gray-400 hover:text-gray-600 text-xs px-1">Annuleer</button>
                                        </div>
                                    </form>
                                </div>
                                @endif
                            @endauth
                        </div>
                        @endforeach
                    </div>
                @endif

                @auth
                    @if(auth()->user()->role === 'admin')
                    <div class="mt-3" x-data="{ adding: false }">
                        <button type="button" @click="adding = true" x-show="!adding"
                                class="text-xs font-semibold text-grape-500 hover:text-grape-700">
                            + Vraag toevoegen
                        </button>
                        <form x-show="adding" x-cloak method="POST" action="{{ route('admin.faq.items.store') }}"
                              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2 mt-2">
                            @csrf
                            <input type="hidden" name="faq_category_id" value="{{ $category->id }}">
                            <input type="text" name="question" placeholder="Vraag" required maxlength="255"
                                   class="w-full text-sm rounded-lg border-gray-300 focus:ring-grape-500 focus:border-grape-500">
                            <textarea name="answer" rows="2" placeholder="Antwoord" required
                                      class="w-full text-sm rounded-lg border-gray-300 focus:ring-grape-500 focus:border-grape-500"></textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-4 py-1.5 rounded-lg text-xs transition">Toevoegen</button>
                                <button type="button" @click="adding = false" class="text-gray-400 hover:text-gray-600 text-xs px-2">Annuleer</button>
                            </div>
                        </form>
                    </div>
                    @endif
                @endauth
            </section>
            @endforeach
        </div>
    @endif

</div>
</x-app-layout>
