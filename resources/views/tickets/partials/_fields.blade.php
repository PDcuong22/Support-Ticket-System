@php
    $ticket = $ticket ?? null;
    $isAdmin = $showAssigned ?? (Auth::check() && optional(Auth::user()->role)->name === 'Admin');
@endphp

{{-- Title --}}
<div class="mb-6">
    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $ticket->title ?? '') }}"
           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
           placeholder="I found a bug" required>
    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

{{-- Description (Message) --}}
<div class="mb-6">
    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Message</label>
    <textarea id="description" name="description" rows="6"
              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500"
              placeholder="Something is wrong, here's the error message: ..." required>{{ old('description', $ticket->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

{{-- Labels --}}
<div class="mb-6">
    <label class="block mb-2 text-sm font-medium text-gray-900">Labels</label>
    <div class="flex gap-4 flex-wrap">
        @foreach($labels as $label)
            <div class="flex items-center">
                <input id="label_{{ $label->id }}" type="checkbox" value="{{ $label->id }}" name="labels[]"
                       {{ in_array($label->id, old('labels', isset($ticket) ? $ticket->labels->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                <label for="label_{{ $label->id }}" class="ml-2 text-sm font-medium text-gray-900">{{ $label->name }}</label>
            </div>
        @endforeach
    </div>
</div>

{{-- Categories --}}
<div class="mb-6">
    <label class="block mb-2 text-sm font-medium text-gray-900">Categories</label>
    <div class="flex gap-4 flex-wrap">
        @foreach($categories as $category)
            <div class="flex items-center">
                <input id="category_{{ $category->id }}" type="checkbox" value="{{ $category->id }}" name="categories[]"
                       {{ in_array($category->id, old('categories', isset($ticket) ? $ticket->categories->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                <label for="category_{{ $category->id }}" class="ml-2 text-sm font-medium text-gray-900">{{ $category->name }}</label>
            </div>
        @endforeach
    </div>
</div>

{{-- Priority --}}
<div class="mb-6">
    <label for="priority_id" class="block mb-2 text-sm font-medium text-gray-900">Priority</label>
    <select id="priority_id" name="priority_id"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" required>
        <option value="">Select priority</option>
        @foreach($priorities as $priority)
            <option value="{{ $priority->id }}" {{ old('priority_id', $ticket->priority_id ?? '') == $priority->id ? 'selected' : '' }}>
                {{ $priority->name }}
            </option>
        @endforeach
    </select>
    @error('priority_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

{{-- Assigned (only for admin) --}}
@if($isAdmin)
<div class="mb-6">
    <label for="assigned_user_id" class="block mb-2 text-sm font-medium text-gray-900">Assign To</label>
    <select name="assigned_user_id" id="assigned_user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5">
        <option value="">-- Unassigned --</option>
        @foreach($agents as $agent)
            <option value="{{ $agent->id }}" {{ old('assigned_user_id', $ticket->assigned_user_id ?? '') == $agent->id ? 'selected' : '' }}>
                {{ $agent->name }} ({{ optional($agent->role)->name }})
            </option>
        @endforeach
    </select>
</div>
@endif