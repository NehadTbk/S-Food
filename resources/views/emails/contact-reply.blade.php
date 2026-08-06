<x-mail::message>
# Antwoord op je bericht

Hallo {{ $contactMessage->name }},

{{ $contactMessage->reply }}

<x-mail::panel>
**Je oorspronkelijke bericht:**

{{ $contactMessage->message }}
</x-mail::panel>

Met vriendelijke groeten,<br>
S-Food
</x-mail::message>
