@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Create User</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
        @csrf

        @include('admin.users._form', ['user' => null, 'submit' => 'Create'])
    </form>
</div>
@endsection