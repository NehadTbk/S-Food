<x-app-layout>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Veelgestelde vragen</h1>

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
                <h2 class="text-sm font-bold text-grape-500 uppercase tracking-wide mb-3">{{ $category->name }}</h2>

                @if($category->faqItems->isEmpty())
                    <p class="text-sm text-gray-400">Nog geen vragen in deze categorie.</p>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100 overflow-hidden">
                        @foreach($category->faqItems as $item)
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left">
                                <span class="text-sm font-medium text-gray-800">{{ $item->question }}</span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform"
                                     :class="{ 'rotate-180': open }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">
                                {{ $item->answer }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </section>
            @endforeach
        </div>
    @endif

</div>
</x-app-layout>
