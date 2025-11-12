@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Edit Category</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        @include('admin.categories._form', ['category' => $category, 'submit' => 'Update'])
    </form>
</div>
@endsection