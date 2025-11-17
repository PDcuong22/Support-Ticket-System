@php
    // use passed variable if available, otherwise fallback to detecting role
    $isAgent = $isAgent ?? (Auth::check() && strtolower(optional(Auth::user()->role)->name ?? '') === 'agent');

    if ($isAgent) {
        $cancelRoute = isset($ticket) ? route('tickets.index', $ticket) : route('tickets.index');
    } else {
        $cancelRoute = route('admin.tickets.index');
    }
@endphp

<div class="mt-6 flex justify-end">
    <a href="{{ $cancelRoute }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-300">Cancel</a>
    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">Update Ticket</button>
</div>