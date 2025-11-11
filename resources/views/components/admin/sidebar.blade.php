<aside class="w-64 bg-gradient-to-b from-purple-900 via-purple-800 to-purple-900 shadow-2xl flex-shrink-0">
    <div class="h-full flex flex-col">
        
        {{-- Logo Section --}}
        <div class="px-6 py-6 border-b border-purple-700/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-lg font-bold text-white tracking-tight leading-tight">Admin Panel</h1>
                    <p class="text-xs text-purple-300 font-medium">Support System</p>
                </div>
            </div>
        </div>

        {{-- Navigation Component --}}
        <x-admin.navigation />

        {{-- User Profile Component --}}
        <x-admin.user-profile />

    </div>
</aside>