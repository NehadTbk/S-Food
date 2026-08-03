<x-admin-layout>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-xl font-bold text-gray-800">Gebruikers</h1>
        <div class="flex gap-2 items-center">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Zoek..."
                       class="text-sm rounded-lg border-gray-300 py-1.5 focus:ring-grape-500 focus:border-grape-500 w-36">
                <select name="role" onchange="this.form.submit()"
                        class="text-sm rounded-lg border-gray-300 py-1.5 focus:ring-grape-500 focus:border-grape-500">
                    <option value="">Alle rollen</option>
                    <option value="user"      {{ request('role') === 'user'      ? 'selected' : '' }}>Gebruiker</option>
                    <option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                    <option value="deliverer" {{ request('role') === 'deliverer' ? 'selected' : '' }}>Bezorger</option>
                </select>
            </form>
            <a href="{{ route('admin.users.create') }}"
               class="bg-grape-500 hover:bg-grape-600 text-white text-sm font-semibold px-4 py-1.5 rounded-lg transition whitespace-nowrap">
                + Gebruiker
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-grape-50 border border-grape-200 text-grape-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    @php
        $roleLabels = ['user' => 'Gebruiker', 'admin' => 'Admin', 'deliverer' => 'Bezorger'];
        $roleClasses = ['user' => 'bg-gray-100 text-gray-600', 'admin' => 'bg-grape-100 text-grape-700', 'deliverer' => 'bg-blue-100 text-blue-700'];
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Naam</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">E-mail</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Rol</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actief</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            {{-- Avatar initial --}}
                            <div class="w-8 h-8 rounded-full bg-grape-100 text-grape-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</p>
                                @if($user->username)
                                    <p class="text-xs text-gray-400">@{{ $user->username }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">{{ $user->email }}</td>

                    {{-- Role update form --}}
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex gap-1.5 items-center">
                            @csrf @method('PATCH')
                            <select name="role" onchange="this.form.submit()"
                                    class="text-xs rounded-lg border-gray-200 py-1 focus:ring-grape-500 focus:border-grape-500 {{ $roleClasses[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                <option value="user"      {{ $user->role === 'user'      ? 'selected' : '' }}>Gebruiker</option>
                                <option value="admin"     {{ $user->role === 'admin'     ? 'selected' : '' }}>Admin</option>
                                <option value="deliverer" {{ $user->role === 'deliverer' ? 'selected' : '' }}>Bezorger</option>
                            </select>
                        </form>
                    </td>

                    {{-- Active toggle --}}
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-10 h-6 rounded-full transition-colors {{ $user->active ? 'bg-grape-500' : 'bg-gray-200' }} relative"
                                    title="{{ $user->active ? 'Deactiveren' : 'Activeren' }}"
                                    {{ $user->id === auth()->id() ? 'disabled title=Jezelf kun je niet deactiveren' : '' }}>
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform {{ $user->active ? 'translate-x-4' : '' }}"></span>
                            </button>
                        </form>
                    </td>

                    <td class="px-4 py-3 text-right text-xs text-gray-400">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
