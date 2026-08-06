@props([
    'margin' => 'mb-4',
    'successClass' => 'bg-grape-50 border-grape-200 text-grape-700',
    'errorClass' => 'bg-red-50 border-red-200 text-red-700',
])

@if(session('success'))
    <div class="{{ $margin }} border rounded-xl px-4 py-3 text-sm {{ $successClass }}">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="{{ $margin }} border rounded-xl px-4 py-3 text-sm {{ $errorClass }}">{{ session('error') }}</div>
@endif
