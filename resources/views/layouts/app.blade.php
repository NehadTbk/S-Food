<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'S-Food') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <script>
        function cartWidget() {
            return {
                count: 0,
                total: '0,00',
                init() {
                    this.fetch();
                    window.addEventListener('cart-updated', () => this.fetch());
                },
                fetch() {
                    fetch('/winkelmandje/samenvatting')
                        .then(r => r.json())
                        .then(data => {
                            this.count = data.cart.count;
                            this.total = data.cart.total;
                        });
                }
            }
        }
    </script>
    <body class="font-sans antialiased bg-gray-50">

        @include('layouts.navigation')

        <main class="min-h-screen">
            @isset($header)
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    {{ $header }}
                </div>
            </div>
            @endisset
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} S-Food. Alle rechten voorbehouden.
            </div>
        </footer>

    </body>
</html>
