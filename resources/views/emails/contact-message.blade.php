<x-mail::message>
# Nieuw contactbericht

**Naam:** {{ $contactMessage->name }}
**E-mail:** {{ $contactMessage->email }}

{{ $contactMessage->message }}

<x-mail::button :url="config('app.url')">
Bekijk website
</x-mail::button>

Verstuurd op {{ $contactMessage->sent_at->format('d/m/Y H:i') }}
</x-mail::message>
