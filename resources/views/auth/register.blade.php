<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="first_name" value="Voornaam" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="last_name" value="Achternaam" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="E-mailadres" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" value="Telefoonnummer" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4 grid grid-cols-3 gap-3">
            <div class="col-span-2">
                <x-input-label for="street" value="Straat" />
                <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" required autocomplete="address-line1" />
                <x-input-error :messages="$errors->get('street')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="house_number" value="Nummer" />
                <x-text-input id="house_number" class="block mt-1 w-full" type="text" name="house_number" :value="old('house_number')" required />
                <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="bus" value="Bus (optioneel)" />
            <x-text-input id="bus" class="block mt-1 w-full" type="text" name="bus" :value="old('bus')" />
            <x-input-error :messages="$errors->get('bus')" class="mt-2" />
        </div>

        <div class="mt-4 grid grid-cols-3 gap-3">
            <div>
                <x-input-label for="postal_code" value="Postcode" />
                <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" required autocomplete="postal-code" />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>
            <div class="col-span-2">
                <x-input-label for="city" value="Gemeente" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required autocomplete="address-level2" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Wachtwoord" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Wachtwoord bevestigen" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Al een account? Log hier in
            </a>

            <x-primary-button class="ms-4">
                Account aanmaken
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
