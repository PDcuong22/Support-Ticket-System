@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Users</h1>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Create User</a>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" onsubmit="return confirmRoleSubmit(this);">
                                @csrf
                                @method('PATCH')
                                <label for="role-{{ $user->id }}" class="sr-only">Role for {{ $user->name }}</label>
                                <select id="role-{{ $user->id }}" name="role_id" data-current="{{ optional($user->role)->id }}" onchange="return confirmRoleChange(this)" class="border rounded px-2 py-1" aria-label="Change role for {{ $user->name }}">
                                    <option value="">-- None --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ (old('role_id', optional($user->role)->id) == $role->id) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <div class="inline-flex items-center space-x-3">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-sm text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@push('scripts')
<script>
    function confirmRoleChange(select) {
        const form = select.form;
        const current = select.dataset.current ?? '';
        const newVal = select.value;
        if (String(current) === String(newVal)) {
            return false; 
        }
        const roleText = select.options[select.selectedIndex].text;
        const ok = confirm('Change role for this user to "' + roleText + '"?');
        if (!ok) {
            select.value = current;
            return false;
        }
        form.submit();
        return true;
    }

    function confirmRoleSubmit(form) {
        const select = form.querySelector('select[name="role_id"]');
        if (!select) return true;
        const current = select.dataset.current ?? '';
        const newVal = select.value;
        if (String(current) === String(newVal)) return false;
        return confirm('Confirm role change?');
    }
</script>
@endpush

@endsection