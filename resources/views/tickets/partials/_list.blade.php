@php
    $formAction = $formAction ?? request()->url();
    $activeStatus = $status ?? request('status') ?? '';
    $activePriority = $priority ?? request('priority') ?? '';
    $activeCategory = $category ?? request('category') ?? '';

    if (!empty($activeStatus) && !is_numeric($activeStatus)) {
        $found = collect($statuses)->first(fn($s) => strtolower($s->name) === strtolower($activeStatus) || (isset($s->slug) && strtolower($s->slug) === strtolower($activeStatus)));
        if ($found) $activeStatus = (string) $found->id;
    }

    if (!empty($activePriority) && !is_numeric($activePriority) && isset($priorities)) {
        $foundP = collect($priorities)->first(fn($p) => strtolower($p->name) === strtolower($activePriority) || (isset($p->slug) && strtolower($p->slug) === strtolower($activePriority)));
        if ($foundP) $activePriority = (string) $foundP->id;
    }

    if (!empty($activeCategory) && !is_numeric($activeCategory) && isset($categories)) {
        $foundC = collect($categories)->first(fn($c) => strtolower($c->name) === strtolower($activeCategory) || (isset($c->slug) && strtolower($c->slug) === strtolower($activeCategory)));
        if ($foundC) $activeCategory = (string) $foundC->id;
    }
@endphp

{{-- Filters: server-side (GET) --}}
<form id="tickets-filter-form" method="GET" action="{{ $formAction }}" class="mb-6 flex flex-wrap gap-4">
    {{-- Search Input --}}
    <div class="flex-1 min-w-[200px]">
        <input type="text" id="search" data-client-search="title" placeholder="Search tickets..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
            value="{{ request('q', '') }}">
    </div>

    {{-- Status Filter --}}
    <select id="status-filter" name="status"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        <option value="">All Status</option>
        @foreach($statuses as $s)
            @php
                $val = $s->id;
                $selected = ((string)$activeStatus !== '' && (string)$activeStatus === (string)$s->id);
            @endphp
            <option value="{{ $val }}" {{ $selected ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
    </select>
    
    {{-- Priority Filter --}}
    <select id="priority-filter" name="priority"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        <option value="">All Priority</option>
        @foreach($priorities as $p)
            <option value="{{ $p->id }}" {{ ((string)$activePriority === (string)$p->id) ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>
    
    {{-- Category Filter --}}
    <select id="category-filter" name="category"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        <option value="">All Categories</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ ((string)$activeCategory === (string)$c->id) ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
        @endforeach
    </select>

    {{-- Submit button for accessibility (hidden visually) --}}
    <button type="submit" class="sr-only" aria-hidden="true">Apply filters</button>
</form>

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
                    Created At
                </th>
                @if($isAdminView)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Assigned To
                </th>
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
                    @if(!empty($isAdminView) && $isAdminView)
                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                           class="text-purple-600 hover:text-purple-900 font-medium hover:underline">
                            {{ Str::limit($ticket->title, 50) }}
                        </a>
                    @else
                        <a href="{{ route('tickets.show', $ticket->id) }}"
                           class="text-purple-600 hover:text-purple-900 font-medium hover:underline">
                            {{ Str::limit($ticket->title, 50) }}
                        </a>
                    @endif
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

                {{-- ACTIONS For ADMIN --}}
                @if($isAdminView)
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ optional($ticket->assignedUser)->name ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.tickets.edit', $ticket) }}" class="text-purple-600 hover:text-purple-900">Edit</a>
                        <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
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
                <td colspan="{{ $isAdminView ? '8' : '6' }}" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">No tickets found</p>
                        <p class="text-gray-400 text-sm mt-1">Get started by creating a new ticket</p>
                        {{-- hide create button for admin view when list is empty --}}
                        @if(empty($isAdminView))
                        <a href="{{ route('tickets.create') }}"
                            class="mt-4 bg-purple-600 hover:bg-purple-700 text-white font-medium px-6 py-2 rounded-lg transition">
                            Create Ticket
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>

{{-- Auto-submit filters JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('tickets-filter-form');
    if (!form) return;

    function buildAndNavigate() {
        const params = new URLSearchParams();
        const status = (form.querySelector('[name="status"]')?.value || '').trim();
        const priority = (form.querySelector('[name="priority"]')?.value || '').trim();
        const category = (form.querySelector('[name="category"]')?.value || '').trim();

        if (status !== '') params.set('status', status);
        if (priority !== '') params.set('priority', priority);
        if (category !== '') params.set('category', category);

        const qs = params.toString();
        const target = form.action + (qs ? ('?' + qs) : '');
        window.location.href = target;
    }

    // escape regex special characters
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // highlight matches inside title elements
    function highlightTitles(term) {
        const titles = document.querySelectorAll('.ticket-title-text');
        if (!titles.length) return;
        if (!term) {
            titles.forEach(t => {
                // restore plain text (escaped)
                t.textContent = t.dataset.fullTitle || '';
            });
            return;
        }
        const re = new RegExp(escapeRegExp(term), 'ig');
        titles.forEach(t => {
            const original = t.dataset.fullTitle || '';
            // replace matches with <strong> — original is plain text so safe
            const replaced = original.replace(re, match => `<strong class="font-semibold bg-yellow-100 px-0.5 rounded">${match}</strong>`);
            t.innerHTML = replaced;
        });
    }

    // client-side search only on title (incremental)
    function filterClientTitle(term) {
        const rows = document.querySelectorAll('[data-ticket-row]');
        const searchTerm = (term !== undefined) ? term.trim().toLowerCase() : (document.getElementById('search')?.value || '').trim().toLowerCase();

        rows.forEach(row => {
            const titleEl = row.querySelector('.ticket-title-text');
            const title = titleEl ? (titleEl.dataset.fullTitle || titleEl.textContent).trim().toLowerCase() : '';
            const matches = !searchTerm || title.includes(searchTerm);
            row.style.display = matches ? '' : 'none';
        });

        highlightTitles(searchTerm);
    }

    // submit when selects change
    form.querySelectorAll('select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            buildAndNavigate();
        });
    });

    // handle client-side search typing and Enter (do not submit)
    const search = document.getElementById('search');
    if (search) {
        search.addEventListener('input', function () {
            filterClientTitle();
        });
        search.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterClientTitle();
            }
        });
    }

    // prevent default form submit and use clean URL builder (so Enter on selects doesn't do full submit)
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        buildAndNavigate();
    });

    // clicking on status/category pills to set search and highlight
    document.addEventListener('click', function (ev) {
        const el = ev.target.closest('[data-word]');
        if (!el) return;
        const word = (el.dataset.word || '').trim();
        if (!word) return;
        if (search) search.value = word;
        filterClientTitle(word.toLowerCase());
    });

    // initial apply (no term)
    filterClientTitle('');
});
</script>