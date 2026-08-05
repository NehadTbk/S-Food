<x-app-layout>
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Contact</h1>

    @if(session('success'))
        <div class="mb-6 bg-grape-50 border border-grape-200 text-grape-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            <div>
                <x-input-label for="name" value="Naam" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" value="E-mailadres" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="message" value="Bericht" />
                <textarea id="message" name="message" rows="5" required
                          class="block mt-1 w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">{{ old('message') }}</textarea>
                <x-input-error :messages="$errors->get('message')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <x-primary-button>Versturen</x-primary-button>
            </div>
        </form>
    </div>

</div>
</x-app-layout>
