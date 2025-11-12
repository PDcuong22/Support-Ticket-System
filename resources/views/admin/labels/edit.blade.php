@extends('layouts.admin')

@section('title', 'Edit Label')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Edit Label</h1>

    <form action="{{ route('admin.labels.update', $label->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        @include('admin.labels._form', ['label' => $label, 'submit' => 'Update'])
    </form>
</div>
@endsection