@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="grid gap-6">
        <header>
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-3xl font-bold">{{ $project->name }}</h1>

                <div>
                    <a href="{{ route('projects.edit', $project) }}">
                        Update project
                    </a>
                    <form
                        method="POST"
                        action="{{ route('projects.destroy', $project) }}"
                        onsubmit="return confirm('Are you sure?');"
                        class="inline-block"
                    >
                        @csrf
                        @method('DELETE')

                        <x-button variant="danger">Delete project</x-button>
                    </form>
                </div>
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

            <div class="flex flex-col gap-4 mt-4">
                <h4 class="text-md font-semibold">Filters</h4>
                <div class="flex gap-4">
                    <span>Select status:</span>
                    <x-badge>
                        <a href="{{ route('projects.show', $project) }}">
                            All
                        </a>
                    </x-badge>
                    <x-badge
                        :active="request()->query('status') === 'in_progress'"
                    >
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}">
                            In Progress
                        </a>
                    </x-badge>
                    <x-badge
                        :active="request()->query('status') === 'pending'"
                    >
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">
                            Pending
                        </a>
                    </x-badge>
                    <x-badge
                        :active="request()->query('status') === 'completed'"
                    >
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}">
                            Completed
                        </a>
                    </x-badge>
                </div>
                <div class="flex gap-2">
                    <span>Select tag:</span>
                    @foreach ($tags as $tag)
                        <x-badge
                            :active="request()->query('tag') == $tag->name"
                        >
                            <a href="{{ request()->fullUrlWithQuery(['tag' => $tag->name]) }}">
                                {{ $tag->name }}
                            </a>
                        </x-badge>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 grid gap-4">
                @forelse ($project->tasks as $task)
                    <x-card>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <h3 class="font-semibold">{{ $task->title }}</h3>
                                <small>{{ $task->due_at }}</small>
                            </div>
                            <div class="flex">
                                <a
                                    href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                    class="text-xs"
                                >
                                    <x-icon.edit />
                                </a>
                                <form
                                    method="POST"
                                    action="{{ route('projects.tasks.destroy', [$project, $task]) }}"
                                    class="text-xs"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button>
                                        <x-icon.delete />
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($task->tags as $tag)
                                <x-badge>
                                    {{ $tag->name }}
                                </x-badge>
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
