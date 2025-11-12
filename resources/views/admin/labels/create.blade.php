@extends('layouts.admin')

@section('title', 'Create Label')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Create Label</h1>

    <form action="{{ route('admin.labels.store') }}" method="POST" class="space-y-6">
        @csrf

        @include('admin.labels._form', ['label' => null, 'submit' => 'Create'])

    </form>
</div>
@endsection