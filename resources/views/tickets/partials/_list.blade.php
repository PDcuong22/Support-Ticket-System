{{-- Filters --}}
<div class="mb-6 flex flex-wrap gap-4">
    {{-- Search Input --}}
    <div class="flex-1 min-w-[200px]">
        <input type="text" id="search" placeholder="Search tickets..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
    </div>

    {{-- Status Filter --}}
    <select id="status-filter"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        <option value="">All Status</option>
        @foreach($statuses as $status)
        <option value="{{ strtolower($status->name) }}">
            {{ $status->name }}
        </option>
        @endforeach
    </select>

    {{-- Priority Filter --}}
    <select id="priority-filter"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        <option value="">All Priority</option>
        @foreach($priorities as $priority)
        <option value="{{ strtolower($priority->name) }}">
            {{ $priority->name }}
        </option>
        @endforeach
    </select>

    {{-- Category Filter --}}
    <select id="category-filter"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        <option value="">All Categories</option>
        @foreach($categories as $category)
        <option value="{{ strtolower($category->name) }}">
            {{ $category->name }}
        </option>
        @endforeach
    </select>

</div>

{{-- Table --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    ID
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Title
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Priority
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Created
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Updated
                </th>
                @if($isAdminView)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tickets as $ticket)
            <tr class="hover:bg-gray-50 transition" data-ticket-row>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    #{{ $ticket->id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap" data-title>
                    <a href="{{ route('tickets.show', $ticket->id) }}"
                        class="text-purple-600 hover:text-purple-900 font-medium hover:underline">
                        {{ Str::limit($ticket->title, 50) }}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap" data-status="{{ strtolower($ticket->status->name) }}">
                    @php
                    $statusColors = [
                    'Open' => 'bg-blue-100 text-blue-800',
                    'In Progress' => 'bg-yellow-100 text-yellow-800',
                    'Resolved' => 'bg-green-100 text-green-800',
                    'Closed' => 'bg-gray-100 text-gray-800',
                    ];
                    $statusClass = $statusColors[$ticket->status->name] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                        {{ $ticket->status->name }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap" data-priority="{{ strtolower($ticket->priority->name) }}">
                    @php
                    $priorityColors = [
                    'LOW' => 'bg-gray-100 text-gray-800',
                    'MEDIUM' => 'bg-blue-100 text-blue-800',
                    'HIGH' => 'bg-orange-100 text-orange-800',
                    'URGENT' => 'bg-red-100 text-red-800',
                    ];
                    $priorityClass = $priorityColors[$ticket->priority->name] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $priorityClass }}">
                        {{ $ticket->priority->name }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    data-categories="{{ $ticket->categories->pluck('name')->map(fn($name) => strtolower($name))->join(',') }}">
                    @if($ticket->categories->count() > 0)
                    <div class="flex flex-wrap gap-1">
                        @foreach($ticket->categories->take(2) as $category)
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">
                            {{ $category->name }}
                        </span>
                        @endforeach
                        @if($ticket->categories->count() > 2)
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                            +{{ $ticket->categories->count() - 2 }}
                        </span>
                        @endif
                    </div>
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $ticket->created_at->format('M d, Y') }}
                    <br>
                    <span class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $ticket->updated_at->format('M d, Y') }}
                </td>

                {{-- ACTIONS For ADMIN --}}
                @if($isAdminView)
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $isAdminView ? '8' : '7' }}" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No tickets found</p>
                        <p class="text-gray-400 text-sm mt-1">Get started by creating a new ticket</p>
                        <a href="{{ route('tickets.create') }}"
                            class="mt-4 bg-purple-600 hover:bg-purple-700 text-white font-medium px-6 py-2 rounded-lg transition">
                            Create Ticket
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Custom Scripts --}}
<script>
    function filterTickets() {
        const searchTerm = document.getElementById('search').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value.toLowerCase();
        const priorityFilter = document.getElementById('priority-filter').value.toLowerCase();
        const categoryFilter = document.getElementById('category-filter').value.toLowerCase();

        const rows = document.querySelectorAll('[data-ticket-row]');

        rows.forEach(row => {
            // Get data from row
            const title = row.querySelector('[data-title]')?.textContent.toLowerCase() || '';
            const status = row.querySelector('[data-status]')?.dataset.status || '';
            const priority = row.querySelector('[data-priority]')?.dataset.priority || '';
            const categories = row.querySelector('[data-categories]')?.dataset.categories || '';

            // Check filters
            const matchesSearch = title.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesPriority = !priorityFilter || priority === priorityFilter;
            const matchesCategory = !categoryFilter || categories.split(',').includes(categoryFilter);

            // Show/hide row
            if (matchesSearch && matchesStatus && matchesPriority && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('search').addEventListener('input', filterTickets);
    document.getElementById('status-filter').addEventListener('change', filterTickets);
    document.getElementById('priority-filter').addEventListener('change', filterTickets);
    document.getElementById('category-filter').addEventListener('change', filterTickets);
</script>