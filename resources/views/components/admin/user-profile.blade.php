<div class="p-4 border-t border-purple-700/40">
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 transition-colors">
        {{-- Avatar with Status --}}
        <div class="relative flex-shrink-0">
            <div class="w-11 h-11 bg-gradient-to-br from-purple-400 via-pink-400 to-purple-500 rounded-xl flex items-center justify-center text-white text-base font-bold shadow-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-400 border-[3px] border-purple-900 rounded-full"></div>
        </div>
        
        {{-- User Info --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-purple-300">Administrator</p>
        </div>
        
        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
            @csrf
            <button type="submit" 
                    class="p-2 text-purple-300 hover:text-white hover:bg-white/20 rounded-lg transition-all" 
                    title="Logout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </button>
        </form>
    </div>
</div>