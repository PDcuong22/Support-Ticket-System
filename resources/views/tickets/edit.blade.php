<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket') }}
        </h2>
    </x-slot>

    {{-- Page Title --}}
    <x-slot name="title">
        Edit Ticket - Support System
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Ticket #{{ $ticket->id }}</h3>

                {{-- Include shared ticket form partial.
                     Assumes the partial renders the <form> tag itself.
                     Pass variables the partial expects (adjust names if different). --}}
                @include('tickets.partials._form', ['isAgent' => true])
            </div>
        </div>
    </div>
</x-app-layout>