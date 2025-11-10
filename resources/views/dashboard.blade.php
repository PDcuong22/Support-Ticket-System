<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Total Tickets Card --}}
            <a href="{{ route('tickets.index') }}" 
               class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center gap-6">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total tickets</p>
                            <p class="text-3xl font-semibold text-gray-900">{{ $userTicketCount }}</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
