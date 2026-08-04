@php
    use Illuminate\Support\Facades\Storage;
    $itemData = json_encode([
        'id'               => $item->id,
        'name'             => $item->name,
        'short_description'=> $item->short_description,
        'full_description' => $item->full_description,
        'ingredients'      => $item->ingredients,
        'price'            => number_format($item->price, 2, ',', '.'),
        'photo'            => $item->photo,
        'type'             => $item->type,
        'is_vegan'         => $item->is_vegan,
        'allergeens'       => $item->allergeens->map(fn($a) => ['id' => $a->id, 'name' => $a->name]),
    ]);
@endphp

<div x-data="{ qty: 1 }"
     x-show="(activeCategory === null || activeCategory === {{ $item->category_id }}) && (!veganOnly || {{ $item->is_vegan ? 'true' : 'false' }})"
     class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition">

    {{-- Photo --}}
    <div class="relative {{ $compact ? 'h-32' : 'h-44' }} overflow-hidden bg-gray-100">
        @if($item->photo)
            <img src="{{ Storage::url($item->photo) }}"
                 alt="{{ $item->name }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
            @if($item->type === 'daily_special')
                <span class="bg-grape-500 text-white text-xs px-2 py-0.5 rounded-full font-medium">Dagspecial</span>
            @endif
            @if(!$item->active)
                <span class="bg-gray-500 text-white text-xs px-2 py-0.5 rounded-full font-medium">Uitverkocht</span>
            @endif
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 flex flex-col flex-1">
        <div class="flex justify-between items-start gap-2 mb-1">
            <h3 class="font-semibold text-gray-800 text-sm leading-tight flex items-center gap-1.5">
                {{ $item->name }}
                @if($item->is_vegan)
                    <span class="inline-flex items-center gap-1 text-grape-500 text-xs font-semibold shrink-0">
                        <img src="/images/vegan-logo.jpg" alt="" class="w-4 h-4 rounded-full">
                        Vegan
                    </span>
                @endif
            </h3>
            <span class="text-grape-500 font-bold text-sm shrink-0">€{{ number_format($item->price, 2, ',', '.') }}</span>
        </div>

        @if(!$compact)
            <p class="text-xs text-gray-500 leading-relaxed mb-3 flex-1">{{ $item->short_description }}</p>
        @endif

        {{-- Stepper + buttons --}}
        <div class="mt-auto space-y-2">
            <div class="flex items-center gap-2">
                {{-- Stepper --}}
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden text-sm">
                    <button @click="if(qty > 1) qty--"
                            {{ !$item->active ? 'disabled' : '' }}
                            class="px-2 py-1 text-gray-600 hover:bg-gray-100 transition disabled:opacity-40">−</button>
                    <span class="px-2 py-1 min-w-[1.75rem] text-center" x-text="qty"></span>
                    <button @click="if(qty < 99) qty++"
                            {{ !$item->active ? 'disabled' : '' }}
                            class="px-2 py-1 text-gray-600 hover:bg-gray-100 transition disabled:opacity-40">+</button>
                </div>

                {{-- Add to cart --}}
                <button @click="addToCart({{ $item->id }}, qty, @js($item->name))"
                        {{ !$item->active ? 'disabled' : '' }}
                        class="flex-1 bg-grape-500 text-white text-xs py-1.5 rounded-lg hover:bg-grape-600 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ $item->active ? 'In mandje' : 'Uitverkocht' }}
                </button>
            </div>

            {{-- Details button --}}
            <button @click="openModal({{ $itemData }})"
                    class="w-full text-xs text-gray-500 hover:text-grape-500 transition text-center py-1">
                Details bekijken →
            </button>
        </div>
    </div>
</div>
