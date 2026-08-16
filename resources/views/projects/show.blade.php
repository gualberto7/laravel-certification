@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="grid gap-6">
        <header>
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-3xl font-bold">{{ $project->name }}</h1>

                <a href="{{ route('projects.edit', $project) }}">
                    Update project
                </a>
            </div>

            @if ($project->description)
                <p class="mt-2 text-slate-600">
                    {{ $project->description }}
                </p>
            @endif
        </header>

        <section>
            <h2 class="text-xl font-semibold">Tasks</h2>

            <div class="mt-4 grid gap-4">
                @forelse ($project->tasks as $task)
                    <x-card>
                        <h3 class="font-semibold">{{ $task->title }}</h3>

                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($task->tags as $tag)
                                <span class="rounded bg-slate-200 px-2 py-1 text-xs">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </x-card>
                @empty
                    <p>No tasks yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
