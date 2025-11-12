@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Create Category</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
        @csrf

        @include('admin.categories._form', ['category' => null, 'submit' => 'Create'])

    </form>
</div>
@endsection