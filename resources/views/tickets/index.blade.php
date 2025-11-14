<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Tickets') }}
            </h2>

            @php
                $user = auth()->user();
                $isAgent = false;
                if ($user) {
                    if (method_exists($user, 'hasRole')) {
                        $isAgent = $user->hasRole('support_agent') || $user->hasRole('Support Agent');
                    } else {
                        $isAgent = optional($user->role)->name === 'support_agent' || optional($user->role)->name === 'Support Agent';
                    }
                }
            @endphp

            @unless($isAgent)
                <a href="{{ route('tickets.create') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 py-2 rounded-lg transition">
                    + New Ticket
                </a>
            @endunless
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
                    @include('tickets.partials._list', ['formAction' => route('tickets.index'), 'isAdminView' => false, 'isAgent' => $isAgent])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>