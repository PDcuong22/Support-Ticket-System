@extends('layouts.admin')

@section('title', 'Labels')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Labels</h1>
        <a href="{{ route('admin.labels.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Create Label</a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($labels as $label)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $label->name }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('admin.labels.edit', $label->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                <form action="{{ route('admin.labels.destroy', $label->id) }}" method="POST" onsubmit="return confirm('Delete this label?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-sm text-gray-500">No labels found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection