<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left: Logo + nav links --}}
            <div class="flex items-center">
                <a href="/" class="flex items-center shrink-0">
                    <img src="/images/grape-leaf-logo.png" alt="S-Food logo" class="w-12 h-12 object-contain flex-shrink-0">
                    <span class="text-xl font-bold text-black -ml-3 relative z-10">S-Food</span>
                </a>

                <div class="hidden lg:flex items-center space-x-6 ms-8">
                    <a href="/" class="text-sm text-gray-700 hover:text-grape-500 {{ request()->is('/') ? 'text-grape-500 font-semibold' : '' }}">Menu</a>
                    <a href="/nieuws" class="text-sm text-gray-700 hover:text-grape-500 {{ request()->is('nieuws*') ? 'text-grape-500 font-semibold' : '' }}">Nieuws</a>
                    <a href="/faq" class="text-sm text-gray-700 hover:text-grape-500 {{ request()->is('faq') ? 'text-grape-500 font-semibold' : '' }}">FAQ</a>
                    <a href="/over-ons" class="text-sm text-gray-700 hover:text-grape-500 {{ request()->is('over-ons') ? 'text-grape-500 font-semibold' : '' }}">Over ons</a>
                    <a href="/contact" class="text-sm text-gray-700 hover:text-grape-500 {{ request()->is('contact') ? 'text-grape-500 font-semibold' : '' }}">Contact</a>
                </div>
            </div>

            {{-- Right: cart + user --}}
            <div class="hidden lg:flex items-center space-x-4">

                {{-- Cart widget --}}
                @auth
                    @if(auth()->user()->role === 'user')
                        <div x-data="cartWidget()" x-init="init()">
                            <a href="/checkout" id="cart-icon" class="relative flex items-center text-gray-600 hover:text-grape-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-9H5.4M7 13L5.4 5M7 13l-1.5 6h11M10 19a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
                                </svg>
                                <span x-show="count > 0" class="ms-1 text-sm font-medium">
                                    (<span x-text="count"></span>) €<span x-text="total"></span>
                                </span>
                            </a>
                        </div>
                    @endif
                @endauth

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-grape-500 focus:outline-none transition">
                                <span>{{ auth()->user()->first_name }}</span>
                                <svg class="ms-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if(auth()->user()->role === 'user')
                                <x-dropdown-link href="/profiel/{{ auth()->user()->username ?? auth()->user()->id }}">
                                    Mijn profiel
                                </x-dropdown-link>
                                <x-dropdown-link href="/bestellingen">
                                    Mijn bestellingen
                                </x-dropdown-link>
                            @endif
                            @if(auth()->user()->role === 'admin')
                                <x-dropdown-link href="/admin">
                                    Beheerpaneel
                                </x-dropdown-link>
                            @endif
                            @if(auth()->user()->role === 'deliverer')
                                <x-dropdown-link href="/bezorger">
                                    Bezorgerpaneel
                                </x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Uitloggen
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-grape-500">Inloggen</a>
                    <a href="{{ route('register') }}" class="text-sm bg-grape-500 text-white px-4 py-2 rounded-md hover:bg-grape-600 transition">Registreren</a>
                @endauth
            </div>

            {{-- Hamburger --}}
            <div class="flex items-center lg:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden border-t border-gray-100">
        <div class="px-4 py-3 space-y-2">
            <a href="/" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Menu</a>
            <a href="/nieuws" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Nieuws</a>
            <a href="/faq" class="block text-sm text-gray-700 hover:text-grape-500 py-1">FAQ</a>
            <a href="/over-ons" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Over ons</a>
            <a href="/contact" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Contact</a>
        </div>

        <div class="px-4 py-3 border-t border-gray-100 space-y-2">
            @auth
                <div class="text-sm font-medium text-gray-800">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>

                @if(auth()->user()->role === 'user')
                    <a href="/checkout" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Winkelmandje</a>

                    <a href="/profiel/{{ auth()->user()->username ?? auth()->user()->id }}" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Mijn profiel</a>
                    <a href="/bestellingen" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Mijn bestellingen</a>
                @endif
                @if(auth()->user()->role === 'admin')
                    <a href="/admin" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Beheerpaneel</a>
                @endif
                @if(auth()->user()->role === 'deliverer')
                    <a href="/bezorger" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Bezorgerpaneel</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-sm text-red-500 hover:text-red-700 py-1">Uitloggen</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Inloggen</a>
                <a href="{{ route('register') }}" class="block text-sm text-grape-500 font-medium hover:text-grape-600 py-1">Registreren</a>
            @endauth
        </div>
    </div>
</nav>
