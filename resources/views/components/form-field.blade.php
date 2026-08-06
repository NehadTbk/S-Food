@props(['label', 'name', 'required' => false])

<div {{ $attributes }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} @if($required)<span class="text-red-400">*</span>@endif
    </label>
    {{ $slot }}
    @error($name) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>
