@extends('layouts.app')

@section('title', 'Edit Tag')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-bold">Edit Tag</h1>
    </div>

    <form method="POST" action="{{ route('tags.update', $tag) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Name</label>
            <x-text-field type="text" name="name" id="name" value="{{ $tag->name }}" />
            @error('name')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <x-button>Update</x-button>
        </div>
    </form>
</div>
@endsection