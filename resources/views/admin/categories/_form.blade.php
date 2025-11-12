@php
    $name = old('name', $label->name ?? '');
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" name="name" value="{{ $name }}" required
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div class="pt-4">
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
        {{ $submit ?? 'Save' }}
    </button>
    <a href="{{ route('admin.categories.index') }}" class="ml-3 text-sm text-gray-600 hover:underline">Cancel</a>
</div>