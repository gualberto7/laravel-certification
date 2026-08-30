@extends('layouts.app')

@section('title', 'Create Tag')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-bold">Create Tag</h1>
    </div>

    <form method="POST" action="{{ route('tags.store') }}">
        @csrf
        <div>
            <label for="name">Name</label>
            <x-text-field type="text" name="name" id="name" />
            @error('name')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <x-button>Create</x-button>
        </div>
    </form>
</div>
@endsection
