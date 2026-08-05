@php use Illuminate\Support\Facades\Storage; @endphp
<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- VIEW MODE --}}
        <div x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }">

            {{-- Profile card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">

                {{-- Header: photo + name + edit button --}}
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                    {{-- Photo --}}
                    <div class="shrink-0">
                        @if($user->photo)
                            <img src="{{ Storage::url($user->photo) }}"
                                 alt="Profielfoto"
                                 class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-24 h-24 rounded-full bg-grape-100 flex items-center justify-center border-2 border-gray-200">
                                <span class="text-3xl font-bold text-grape-400">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Name + username + edit button --}}
                    <div class="flex-1 text-center sm:text-left">
                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            @{{ $user->username ?? 'gebruiker' }}
                        </p>

                        @auth
                            @if(auth()->id() === $user->id)
                                <button @click="editing = true"
                                        x-show="!editing"
                                        class="mt-3 inline-flex items-center px-4 py-2 bg-grape-500 text-white text-sm rounded-md hover:bg-grape-600 transition">
                                    Bewerken
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- View: birthday + bio --}}
                <div x-show="!editing" class="mt-6 space-y-4">
                    @if($user->birthday)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $user->birthday->format('d/m/Y') }}</span>
                        </div>
                    @endif

                    @if($user->bio)
                        <div class="text-sm text-gray-700 leading-relaxed border-t border-gray-100 pt-4">
                            {{ $user->bio }}
                        </div>
                    @endif

                    @if(!$user->birthday && !$user->bio && auth()->id() !== $user->id)
                        <p class="text-sm text-gray-400 italic">Dit profiel is nog niet ingevuld.</p>
                    @endif
                </div>

                {{-- EDIT FORM --}}
                <div x-show="editing" x-cloak class="mt-6">
                    <form method="POST"
                          action="/profiel/{{ $user->username ?? $user->id }}"
                          enctype="multipart/form-data"
                          class="space-y-5">
                        @csrf
                        @method('PATCH')

                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gebruikersnaam</label>
                            <input type="text"
                                   name="username"
                                   value="{{ old('username', $user->username) }}"
                                   maxlength="50"
                                   class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-grape-400 focus:ring-grape-400"
                                   placeholder="Kies een unieke gebruikersnaam">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Birthday --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Verjaardag</label>
                            <input type="date"
                                   name="birthday"
                                   value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}"
                                   max="{{ now()->subDay()->format('Y-m-d') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-grape-400 focus:ring-grape-400">
                            @error('birthday')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Photo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profielfoto</label>
                            <input type="file"
                                   name="photo"
                                   accept="image/jpeg,image/png"
                                   class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:bg-grape-50 file:text-grape-600 hover:file:bg-grape-100"
                                   onchange="
                                       const file = this.files[0];
                                       if (file && file.size > 2 * 1024 * 1024) {
                                           alert('Foto mag maximaal 2 MB zijn.');
                                           this.value = '';
                                       }
                                   ">
                            <p class="text-xs text-gray-400 mt-1">JPG of PNG, max 2 MB</p>
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bio --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Over mij</label>
                            <textarea name="bio"
                                      rows="4"
                                      maxlength="500"
                                      class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-grape-400 focus:ring-grape-400"
                                      placeholder="Vertel iets over jezelf...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-3 pt-2">
                            <button type="submit"
                                    class="px-5 py-2 bg-grape-500 text-white text-sm rounded-md hover:bg-grape-600 transition">
                                Opslaan
                            </button>
                            <button type="button"
                                    @click="editing = false"
                                    class="px-5 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 transition">
                                Annuleren
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
