@extends('layouts.admin')

@section('title', 'Ticket Logs')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-4">Ticket Logs</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($log->action) }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($log->ticket)
                                <a href="{{ route('admin.tickets.edit', $log->ticket->id) }}" class="text-purple-600 hover:underline">#{{ $log->ticket->id }} — {{ \Illuminate\Support\Str::limit($log->ticket->title, 60) }}</a>
                            @else
                                <span class="text-gray-500">Ticket #{{ $log->ticket_id }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $log->user?->name ?? 'System' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-sm text-gray-500">No logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection