<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Bezorger – {{ config('app.name', 'S-Food') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">

        <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-4">
                        <a href="/bezorger" class="text-xl font-bold text-grape-500">S-Food</a>
                        <span class="text-sm text-gray-400 hidden sm:inline">Bezorgerpaneel</span>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <a href="/bezorger" class="text-sm text-gray-600 hover:text-grape-500 {{ request()->is('bezorger') ? 'text-grape-500 font-semibold' : '' }}">
                            Beschikbare leveringen
                        </a>
                        <a href="/bezorger/leveringen" class="text-sm text-gray-600 hover:text-grape-500 {{ request()->is('bezorger/leveringen*') ? 'text-grape-500 font-semibold' : '' }}">
                            Mijn leveringen
                        </a>

                        <div class="text-sm text-gray-500">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700">Uitloggen</button>
                        </form>
                    </div>

                    <button @click="open = !open" class="md:hidden p-2 rounded-md text-gray-400 hover:bg-gray-100">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden border-t border-gray-100 px-4 py-3 space-y-2">
                <div class="text-sm font-medium text-gray-800 py-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                <a href="/bezorger" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Beschikbare leveringen</a>
                <a href="/bezorger/leveringen" class="block text-sm text-gray-700 hover:text-grape-500 py-1">Mijn leveringen</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-sm text-red-500 hover:text-red-700 py-1">Uitloggen</button>
                </form>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

    </body>
</html>
