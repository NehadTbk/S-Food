<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Beheer – {{ config('app.name', 'S-Food') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $newContactCount = \App\Models\ContactMessage::whereNull('replied_at')->count();
        $newOrderCount = \App\Models\Order::where('status', 'new')->count();
    @endphp
    <body class="font-sans antialiased bg-gray-100">

        {{-- Top bar --}}
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-4">
                        <a href="/admin" class="text-xl font-bold text-grape-500">S-Food</a>
                        <span class="text-sm text-gray-400 hidden sm:inline">Beheerpaneel</span>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <a href="/admin" class="text-sm text-gray-600 hover:text-grape-500 {{ request()->is('admin') ? 'text-grape-500 font-semibold' : '' }}">Dashboard</a>
                        <a href="/admin/bestellingen" class="text-sm text-gray-600 hover:text-grape-500 inline-flex items-center gap-1.5 {{ request()->is('admin/bestellingen*') ? 'text-grape-500 font-semibold' : '' }}">
                            Bestellingen
                            @if($newOrderCount > 0)
                                <span class="inline-flex items-center justify-center min-w-[0.75rem] h-3 px-0.5 text-[9px] leading-3 font-semibold text-white bg-grape-500 rounded-full">{{ $newOrderCount }}</span>
                            @endif
                        </a>
                        <a href="/admin/gebruikers" class="text-sm text-gray-600 hover:text-grape-500 {{ request()->is('admin/gebruikers*') ? 'text-grape-500 font-semibold' : '' }}">Gebruikers</a>
                        <a href="/admin/contact" class="text-sm text-gray-600 hover:text-grape-500 inline-flex items-center gap-1.5 {{ request()->is('admin/contact*') ? 'text-grape-500 font-semibold' : '' }}">
                            Contact
                            @if($newContactCount > 0)
                                <span class="inline-flex items-center justify-center min-w-[0.75rem] h-3 px-0.5 text-[9px] leading-3 font-semibold text-white bg-red-500 rounded-full">{{ $newContactCount }}</span>
                            @endif
                        </a>
                        <a href="/" class="text-sm text-gray-400 hover:text-gray-600">← Naar site</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700">Uitloggen</button>
                        </form>
                    </div>

                    {{-- Mobile hamburger --}}
                    <button @click="open = !open" class="md:hidden p-2 rounded-md text-gray-400 hover:bg-gray-100">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden border-t border-gray-100 px-4 py-3 space-y-2">
                <a href="/admin" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Dashboard</a>
                <a href="/admin/bestellingen" class="block text-sm text-gray-700 hover:text-grape-500 py-1 inline-flex items-center gap-1.5">
                    Bestellingen
                    @if($newOrderCount > 0)
                        <span class="inline-flex items-center justify-center min-w-[0.75rem] h-3 px-0.5 text-[9px] leading-3 font-semibold text-white bg-grape-500 rounded-full">{{ $newOrderCount }}</span>
                    @endif
                </a>
                <a href="/admin/gebruikers" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Gebruikers</a>
                <a href="/admin/contact" class="block text-sm text-gray-700 hover:text-grape-500 py-1 inline-flex items-center gap-1.5">
                    Contact
                    @if($newContactCount > 0)
                        <span class="inline-flex items-center justify-center min-w-[0.75rem] h-3 px-0.5 text-[9px] leading-3 font-semibold text-white bg-red-500 rounded-full">{{ $newContactCount }}</span>
                    @endif
                </a>
                <a href="/" class="block text-sm text-gray-400 hover:text-gray-600 py-1">← Naar site</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-sm text-red-500 hover:text-red-700 py-1">Uitloggen</button>
                </form>
            </div>
        </nav>

        {{-- Page content --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

    </body>
</html>
