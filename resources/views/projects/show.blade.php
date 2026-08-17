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
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">Tasks</h2>
                <a
                    href="{{ route('projects.tasks.create', $project) }}"
                    class="rounded bg-blue-500 px-3 py-1 text-white"
                >
                    Create task
                </a>
            </div>

            <div class="mt-4 grid gap-4">
                @forelse ($project->tasks as $task)
                    <x-card>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <h3 class="font-semibold">{{ $task->title }}</h3>
                                <small>{{ $task->due_at }}</small>
                            </div>
                            <div>
                                <a
                                    href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                    class="text-xs"
                                >
                                    Edit
                                </a>
                                <form
                                    method="POST"
                                    action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                    class="text-xs"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button>Delete</button>
                                </form>
                            </div>
                        </div>

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
