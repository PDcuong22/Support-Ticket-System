@extends('layouts.admin')

@section('title', 'Edit Ticket #' . $ticket->id)

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Ticket Details</h3>

    @include('tickets.partials._form')
</div>
@endsection