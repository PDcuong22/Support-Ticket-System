<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar Component --}}
        <x-admin.sidebar />

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
            
            {{-- Header Component --}}
            <x-admin.header :title="$title ?? 'Admin Dashboard'" />

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-8 py-6">
                    
                    {{-- Flash Messages Component --}}
                    <x-admin.flash-messages />

                    {{-- Page Content --}}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    {{-- Footer Scripts --}}
    @stack('scripts')
</body>
</html>