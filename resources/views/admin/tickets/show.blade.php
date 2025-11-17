@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">#{{ $ticket->id }} — {{ $ticket->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Created: {{ $ticket->created_at->format('M d, Y H:i') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.tickets.index') }}" class="text-gray-600 hover:text-gray-900">Back to Tickets</a>
            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="ml-4 bg-indigo-600 text-white px-3 py-1.5 rounded">Edit</a>
        </div>
    </div>

    <section class="mb-8">
        <h2 class="text-lg font-medium text-gray-800 mb-2">Description</h2>
        <div class="prose max-w-none text-gray-700 border border-gray-100 rounded p-4 bg-gray-50">
            {!! nl2br(e($ticket->description)) !!}
        </div>
    </section>

    @include('tickets.partials.comments', [
        'ticket' => $ticket,
    ])
</div>
@endsection