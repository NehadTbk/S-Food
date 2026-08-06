@props(['colorClasses' => 'bg-gray-100 text-gray-600'])

<span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $colorClasses }}">{{ $slot }}</span>
