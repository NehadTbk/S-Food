<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="menuPage()"
     x-init="init()">

    {{-- ===================== DAILY SPECIALS ===================== --}}
    @if($dailySpecials->isNotEmpty())
    <section class="mb-10">
        <h2 class="text-lg font-bold text-grape-500 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Vandaag speciaal
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($dailySpecials as $item)
                @include('home.partials.menu-card', ['item' => $item, 'compact' => true])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ===================== CATEGORY TABS + VEGAN FILTER ===================== --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="overflow-x-auto">
            <div class="flex gap-2 min-w-max">
                <button @click="activeCategory = null"
                        :class="activeCategory === null ? 'bg-grape-500 text-white' : 'bg-white text-gray-600 hover:bg-grape-50 border border-gray-200'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
                    Alles
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = {{ $category->id }}"
                        :class="activeCategory === {{ $category->id }} ? 'bg-grape-500 text-white' : 'bg-white text-gray-600 hover:bg-grape-50 border border-gray-200'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>

        <button @click="veganOnly = !veganOnly"
                :class="veganOnly ? 'bg-grape-500 text-white' : 'bg-white text-gray-600 hover:bg-grape-50 border border-gray-200'"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap self-start">
            <img src="/images/vegan-logo.jpg" alt="" class="w-4 h-4 rounded-full">
            Enkel vegan
        </button>
    </div>

    {{-- ===================== MENU ITEMS GRID ===================== --}}
    @if($regularItems->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-9H5.4M7 13L5.4 5M7 13l-1.5 6h11"/>
            </svg>
            <p class="text-sm">Nog geen gerechten beschikbaar.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($regularItems as $item)
                @include('home.partials.menu-card', ['item' => $item, 'compact' => false])
            @endforeach
        </div>
    @endif

    {{-- ===================== DETAIL MODAL ===================== --}}
    <div x-show="modal !== null"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="modal = null">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" @click="modal = null"></div>

        {{-- Modal content --}}
        <div x-show="modal !== null"
             x-transition
             class="relative bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10">

            <template x-if="modal !== null">
                <div>
                    {{-- Photo --}}
                    <div class="relative">
                        <img :src="modal.photo ? '/storage/' + modal.photo : '/images/placeholder.jpg'"
                             :alt="modal.name"
                             class="w-full h-52 object-cover rounded-t-xl">
                        <button @click="modal = null"
                                class="absolute top-3 right-3 bg-white rounded-full p-1 shadow hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Name + price --}}
                        <div class="flex justify-between items-start gap-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <span x-text="modal.name"></span>
                                <template x-if="modal.is_vegan">
                                    <span class="inline-flex items-center gap-1 text-grape-500 text-xs font-semibold shrink-0">
                                        <img src="/images/vegan-logo.jpg" alt="" class="w-4 h-4 rounded-full">
                                        Vegan
                                    </span>
                                </template>
                            </h3>
                            <span class="text-grape-500 font-bold text-lg shrink-0">
                                €<span x-text="modal.price"></span>
                                <span class="text-xs font-normal text-gray-400">/ persoon</span>
                            </span>
                        </div>

                        {{-- Full description --}}
                        <p class="text-sm text-gray-600 leading-relaxed" x-text="modal.full_description"></p>

                        {{-- Ingredients --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Ingrediënten</p>
                            <p class="text-sm text-gray-600" x-text="modal.ingredients"></p>
                        </div>

                        {{-- Allergens --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Allergenen</p>
                            <template x-if="modal.allergeens && modal.allergeens.length > 0">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="a in modal.allergeens" :key="a.id">
                                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 text-xs rounded-full border border-yellow-200" x-text="a.name"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!modal.allergeens || modal.allergeens.length === 0">
                                <p class="text-sm text-gray-400">Geen allergenen bekend</p>
                            </template>
                        </div>

                        {{-- Stepper + add to cart --}}
                        <div class="flex items-center gap-4 pt-2">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button @click="if(modalQty > 1) modalQty--"
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition text-lg font-bold">−</button>
                                <span class="px-4 py-2 text-sm font-medium min-w-[2.5rem] text-center" x-text="modalQty"></span>
                                <button @click="if(modalQty < 99) modalQty++"
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition text-lg font-bold">+</button>
                            </div>
                            <button @click="addToCart(modal.id, modalQty, modal.name); modal = null"
                                    class="flex-1 bg-grape-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-grape-600 transition">
                                In winkelmandje
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>

<script>
function menuPage() {
    return {
        activeCategory: null,
        veganOnly: false,
        modal: null,
        modalQty: 1,

        init() {
            // Show/hide cards based on active category
        },

        openModal(item) {
            this.modal = item;
            this.modalQty = 1;
        },

        addToCart(menuItemId, quantity, itemName) {
            @auth
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ menu_item_id: menuItemId, quantity: quantity }),
            })
            .then(r => r.json())
            .then(data => {
                window.dispatchEvent(new CustomEvent('cart-updated'));
                this.showToast(itemName);
            })
            .catch(() => alert('Er ging iets mis. Probeer opnieuw.'));
            @else
            window.location.href = '{{ route('login') }}';
            @endauth
        },

        showToast(itemName) {
            const toast = document.createElement('div');
            toast.className = 'fixed flex items-center gap-1.5 bg-grape-50 border border-grape-200 text-grape-700 text-xs px-3 py-2 rounded-xl shadow-lg z-50 opacity-0 translate-x-2 transition-all duration-300';
            toast.innerHTML = '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span></span>';
            toast.querySelector('span').textContent = itemName + ' toegevoegd aan winkelmandje!';

            const cartIcon = document.getElementById('cart-icon');
            let reposition = null;
            let arrow = null;

            if (cartIcon) {
                arrow = document.createElement('div');
                arrow.className = 'absolute top-1/2 -right-2 -translate-y-1/2 w-0 h-0 border-t-8 border-b-8 border-l-8 border-t-transparent border-b-transparent border-l-grape-50';
                toast.appendChild(arrow);

                document.body.appendChild(toast);

                reposition = () => {
                    if (cartIcon.offsetParent === null) {
                        arrow.style.display = 'none';
                        toast.style.top = '1rem';
                        toast.style.right = '1rem';
                        return;
                    }
                    arrow.style.display = '';
                    const rect = cartIcon.getBoundingClientRect();
                    const toastRect = toast.getBoundingClientRect();
                    toast.style.top = (rect.top + rect.height / 2 - toastRect.height / 2) + 'px';
                    toast.style.right = (window.innerWidth - rect.left + 8) + 'px';
                };
                reposition();
                window.addEventListener('resize', reposition);
            } else {
                toast.style.top = '1rem';
                toast.style.right = '1rem';
                document.body.appendChild(toast);
            }

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-x-2');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-2');
                setTimeout(() => {
                    toast.remove();
                    if (reposition) window.removeEventListener('resize', reposition);
                }, 300);
            }, 3000);
        }
    }
}
</script>
</x-app-layout>
