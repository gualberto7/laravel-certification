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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                         <a href="{{ route('tags.edit', $tag) }}" class="text-sm text-white cursor-pointer hover:bg-gray-400 p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <span>No tags yet.</span>
            @endforelse
            </div>
    </x-card>
@endsection