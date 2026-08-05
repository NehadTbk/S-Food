<x-admin-layout>
    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Gebruiker aanmaken</h1>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Voornaam <span class="text-red-400">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('first_name') border-red-400 @enderror">
                    @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Achternaam <span class="text-red-400">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('last_name') border-red-400 @enderror">
                    @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mailadres <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('email') border-red-400 @enderror">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer <span class="text-red-400">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('phone') border-red-400 @enderror">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-400">*</span></label>
                    <select name="role" required
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('role') border-red-400 @enderror">
                        <option value="user"      {{ old('role') === 'user'      ? 'selected' : '' }}>Gebruiker</option>
                        <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                        <option value="deliverer" {{ old('role') === 'deliverer' ? 'selected' : '' }}>Bezorger</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Address --}}
            <div class="border-t border-gray-100 pt-4">
                <p class="text-sm font-medium text-gray-700 mb-3">Adres</p>
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div class="col-span-2">
                        <input type="text" name="street" value="{{ old('street') }}" placeholder="Straat" required
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('street') border-red-400 @enderror">
                        @error('street') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" name="house_number" value="{{ old('house_number') }}" placeholder="Nr." required
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('house_number') border-red-400 @enderror">
                        @error('house_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" name="bus" value="{{ old('bus') }}" placeholder="Bus (optioneel)"
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="Postcode" required
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('postal_code') border-red-400 @enderror">
                        @error('postal_code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-2">
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="Gemeente" required
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('city') border-red-400 @enderror">
                        @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="border-t border-gray-100 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord <span class="text-red-400">*</span></label>
                    <input type="password" name="password" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500 @error('password') border-red-400 @enderror">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bevestig wachtwoord <span class="text-red-400">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-grape-500 focus:border-grape-500">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-grape-500 hover:bg-grape-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                    Account aanmaken
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                    Annuleer
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
