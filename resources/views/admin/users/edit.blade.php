@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Edit User</h1>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        @include('admin.users._form', ['user' => $user, 'submit' => 'Update'])
    </form>
</div>
@endsection