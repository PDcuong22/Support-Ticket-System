<div class="lg:col-span-1 space-y-6">
    
    {{-- Status & Priority --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Details</h3>
            
            <div class="space-y-4">
                {{-- Status --}}
                <div>
                    <label class="text-sm font-medium text-gray-500 block mb-2">Status</label>
                    @php
                        $statusColors = [
                            'Open' => 'bg-blue-100 text-blue-800',
                            'In Progress' => 'bg-yellow-100 text-yellow-800',
                            'Resolved' => 'bg-green-100 text-green-800',
                            'Closed' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusClass = $statusColors[$ticket->status->name] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-lg {{ $statusClass }} w-full justify-center">
                        {{ $ticket->status->name }}
                    </span>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="text-sm font-medium text-gray-500 block mb-2">Priority</label>
                    @php
                        $priorityColors = [
                            'LOW' => 'bg-gray-100 text-gray-800',
                            'MEDIUM' => 'bg-blue-100 text-blue-800',
                            'HIGH' => 'bg-orange-100 text-orange-800',
                            'URGENT' => 'bg-red-100 text-red-800',
                        ];
                        $priorityClass = $priorityColors[$ticket->priority->name] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-lg {{ $priorityClass }} w-full justify-center">
                        {{ $ticket->priority->name }}
                    </span>
                </div>

                {{-- Assigned To --}}
                @if($ticket->assignedUser)
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-2">Assigned To</label>
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-semibold text-xs">
                                    {{ substr($ticket->assignedUser->name, 0, 1) }}
                                </span>
                            </div>
                            <span class="text-sm text-gray-900">{{ $ticket->assignedUser->name }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Categories --}}
    @if($ticket->categories && $ticket->categories->count() > 0)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($ticket->categories as $category)
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-medium rounded-lg">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</div>