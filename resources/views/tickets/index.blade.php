<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Tickets') }}
            </h2>
            <a href="{{ route('tickets.create') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 py-2 rounded-lg transition">
                + New Ticket
            </a>
        </div>
    </x-slot>

    {{-- Page Title --}}
    <x-slot name="title">
        Tickets - Support System
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @include('tickets.partials._list', ['formAction' => route('tickets.index'), 'isAdminView' => false])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>