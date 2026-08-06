<x-app-layout>
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Contact</h1>

    <x-flash-messages margin="mb-6" />

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            <x-form-field label="Naam" name="name" required>
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                              :value="old('name', auth()->check() ? trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) : '')" required autofocus />
            </x-form-field>

            <div class="mt-4">
                <x-form-field label="E-mailadres" name="email" required>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                  :value="old('email', auth()->user()?->email)" required />
                </x-form-field>
            </div>

            <div class="mt-4">
                <x-form-field label="Bericht" name="message" required>
                    <textarea id="message" name="message" rows="5" required
                              class="block mt-1 w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">{{ old('message') }}</textarea>
                </x-form-field>
            </div>

            <div class="flex justify-end mt-6">
                <x-primary-button>Versturen</x-primary-button>
            </div>
        </form>
    </div>

</div>
</x-app-layout>
