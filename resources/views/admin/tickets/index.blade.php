@extends('layouts.admin')

@section('title', 'Manage Tickets')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">All Tickets ({{ $tickets->total() }})</h3>
            <p class="text-sm text-gray-500 mt-0.5">Manage and track all support tickets</p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            @include('tickets.partials._list', ['isAdminView' => true])
        </div>
    </div>
@endsection