<x-mail::message>
# New ticket created — #{{ $ticket->id }}

Title: {{ $ticket->title }}

@if(!empty($adminEditUrl))
<x-mail::button :url="$adminEditUrl">
View / Edit ticket
</x-mail::button>
@else
<p class="text-sm text-gray-600">Edit link not available.</p>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>