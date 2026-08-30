@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-bold">Tags</h1>
    </div>

    <x-card class="mt-6">
        <div class="flex flex-wrap gap-5">
            @forelse ($tags as $tag)
                <div class="bg-blue-400 flex items-start gap-5 rounded-2xl py-1 px-3">
                    <div class="flex flex-col">
                        <span class="text-white">{{ $tag->name }}</span>
                        <small class="text-xs">{{ $tag->tasks_count }} tasks</small>
                    </div>
                    <button class="text-sm text-white cursor-pointer hover:bg-gray-400 p-1">x</button>
                </div>
            @empty
                <span>No tags yet.</span>
            @endforelse
            </div>
    </x-card>
@endsection