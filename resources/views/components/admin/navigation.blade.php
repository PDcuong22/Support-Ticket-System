<nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-purple-700 scrollbar-track-transparent">
    
    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" 
       class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-purple-900 shadow-lg' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'bg-purple-100' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-purple-900' : 'text-purple-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
        </div>
        <span>Dashboard</span>
    </a>

    {{-- Tickets --}}
    <a href="{{ route('admin.tickets') }}" 
       class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.tickets') ? 'bg-white text-purple-900 shadow-lg' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.tickets') ? 'bg-purple-100' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.tickets') ? 'text-purple-900' : 'text-purple-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
        </div>
        <span>Tickets</span>
    </a>

    {{-- Users --}}
    <a href="{{ route('admin.users') }}" 
       class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-white text-purple-900 shadow-lg' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.users') ? 'bg-purple-100' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.users') ? 'text-purple-900' : 'text-purple-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <span>Users</span>
    </a>

    {{-- Ticket Logs --}}
    <a href="{{ route('admin.ticket-logs') }}" 
       class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.ticket-logs') ? 'bg-white text-purple-900 shadow-lg' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.ticket-logs') ? 'bg-purple-100' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.ticket-logs') ? 'text-purple-900' : 'text-purple-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <span>Ticket Logs</span>
    </a>

    {{-- Divider --}}
    <div class="my-3 border-t border-purple-700/40"></div>

    {{-- Categories --}}
    <a href="{{ route('admin.categories') }}" 
       class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.categories') ? 'bg-white text-purple-900 shadow-lg' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.categories') ? 'bg-purple-100' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.categories') ? 'text-purple-900' : 'text-purple-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <span>Categories</span>
    </a>

    {{-- Labels --}}
    <a href="{{ route('admin.labels') }}" 
       class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.labels') ? 'bg-white text-purple-900 shadow-lg' : 'text-purple-100 hover:bg-white/10 hover:text-white' }}">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.labels') ? 'bg-purple-100' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.labels') ? 'text-purple-900' : 'text-purple-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <span>Labels</span>
    </a>

</nav>