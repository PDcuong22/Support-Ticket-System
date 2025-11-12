@php
    $name = old('name', $user->name ?? '');
    $email = old('email', $user->email ?? '');
    $roleId = old('role_id', $user->role_id ?? '');
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" name="name" value="{{ $name }}" required
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ $email }}" required
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Role</label>
    <select name="role_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
        <option value="">-- Select role --</option>
        @if(isset($roles) && $roles->count())
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        @else
            {{-- Fallback: if roles not provided, try single relation --}}
            @if(isset($user->role))
                <option value="{{ $user->role->id }}" selected>{{ $user->role->name }}</option>
            @endif
        @endif
    </select>
    @error('role_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">
        Password
        @if(isset($user)) <span class="text-xs text-gray-500">(leave blank to keep current)</span> @endif
    </label>
    <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
    @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
    <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
</div>

<div class="pt-4">
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
        {{ $submit ?? 'Save' }}
    </button>
    <a href="{{ route('admin.users.index') }}" class="ml-3 text-sm text-gray-600 hover:underline">Cancel</a>
</div>