@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-bold">Tags</h1>
        <a href="{{ route('tags.create') }}">Add Tag</a>
    </div>

    <x-card class="mt-6">
        <div class="flex flex-wrap gap-5">
            @forelse ($tags as $tag)
                <div class="bg-blue-400 flex items-start gap-5 rounded-2xl py-1 px-3">
                    <div class="flex flex-col">
                        <span class="text-white">{{ $tag->name }}</span>
                        <small class="text-xs">{{ $tag->tasks_count }} tasks</small>
                    </div>
                    <div class="flex flex-col">
                        <form method="POST" action="{{ route('tags.destroy', $tag) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm text-white cursor-pointer hover:bg-gray-400 p-1">
                                <x-icon.delete />
                            </button>
                        </form>
                         <a href="{{ route('tags.edit', $tag) }}" class="text-sm text-white cursor-pointer hover:bg-gray-400 p-1">
                            <x-icon.edit />
                        </a>
                    </div>
                </div>
            @empty
                <span>No tags yet.</span>
            @endforelse
            </div>
    </x-card>
@endsection