@extends('layouts.admin')

@section('title', 'Edit Ticket #' . $ticket->id)

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Ticket Details</h3>

    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="user_id" value="{{ $ticket->user_id }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $ticket->title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
            </div>

            {{-- ASSIGN TO --}}
            <div>
                <label for="assigned_user_id" class="block text-sm font-medium text-gray-700">Assign To</label>
                <select name="assigned_user_id" id="assigned_user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Unassigned --</option>
                    @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ old('assigned_user_id', $ticket->assigned_user_id) == $agent->id ? 'selected' : '' }}>
                        {{ $agent->name }} ({{ $agent->role->name }})
                    </option>
                    @endforeach
                </select>
            </div>
            {{-- Priority --}}
            <div>
                <label for="priority_id" class="block text-sm font-medium text-gray-700">Priority</label>
                <select name="priority_id" id="priority_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Select priority --</option>
                    @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ old('priority_id', $ticket->priority_id) == $priority->id ? 'selected' : '' }}>
                        {{ ucfirst($priority->name) }}
                    </option>
                    @endforeach
                </select>
            </div>
            {{-- Status --}}
            <div>
                <label for="status_id" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status_id" id="status_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Select status --</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ old('status_id', $ticket->status_id) == $status->id ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status->name)) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Categories</label>
                @php
                $selectedCategories = old(
                'category_ids',
                isset($ticket->categories) ? $ticket->categories->pluck('id')->toArray() : (isset($ticket->category_id) ? [$ticket->category_id] : [])
                );
                @endphp
                <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($categories as $category)
                    <label class="inline-flex items-center space-x-2 px-3 py-2 border rounded-md text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, (array) $selectedCategories) ? 'checked' : '' }}
                            class="h-4 w-4 text-purple-600 border-gray-300 rounded">
                        <span class="text-gray-700">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="mt-1 text-xs text-gray-500">Select one or more categories.</p>
            </div>

            {{-- Labels --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Labels</label>
                @php
                $selectedLabels = old('label_ids', isset($ticket->labels) ? $ticket->labels->pluck('id')->toArray() : []);
                @endphp
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($labels as $label)
                    <label class="inline-flex items-center space-x-2 px-3 py-1 border rounded-full text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="labels[]" value="{{ $label->id }}"
                            {{ in_array($label->id, (array) $selectedLabels) ? 'checked' : '' }}
                            class="h-4 w-4 text-purple-600 border-gray-300 rounded">
                        <span class="text-sm">{{ $label->name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="mt-1 text-xs text-gray-500">Attach labels to the ticket.</p>
            </div>

        </div>

        {{-- Description --}}
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">{{ old('description', $ticket->description) }}</textarea>
        </div>  

        {{-- Attachments --}}
        <div class="mt-6">
            <h4 class="text-md font-medium text-gray-800 mb-3">Current Attachments</h4>
            @if($ticket->attachments && $ticket->attachments->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($ticket->attachments as $attachment)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    {{-- Link to file --}}
                    <a href="{{ Storage::url($attachment->file_path) }}"
                        target="_blank"
                        class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            @if(Str::startsWith($attachment->mime_type, 'image/'))
                            <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $attachment->file_name }}">
                                {{ $attachment->file_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ number_format($attachment->file_size / 1024, 2) }} KB
                            </p>
                        </div>
                    </a>
                    {{-- Remove Button --}}
                    <!-- <div class="ml-4 flex-shrink-0">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this attachment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors" title="Remove attachment">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div> -->
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500">This ticket has no attachments.</p>
            @endif
        </div>

        {{-- Submit Button --}}
        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.tickets.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-300">Cancel</a>
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">Update Ticket</button>
        </div>
    </form>
</div>
@endsection