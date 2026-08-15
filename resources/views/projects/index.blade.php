@extends('layouts.app')

@section('title', 'Projects')

@use('Illuminate\Support\Str')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-bold">Projects</h1>

        <a href="{{ route('projects.create') }}">
            Create project
        </a>
    </div>

    <div class="mt-6 grid gap-4">
        @forelse ($projects as $project)
            <x-card>
                <a
                    href="{{ route('projects.show', $project) }}"
                    class="text-lg font-semibold"
                >
                    {{ $project->name }}
                </a>

                <p class="text-sm text-slate-500">
                    {{ $project->tasks_count }}
                    {{ Str::plural('task', $project->tasks_count) }}
                </p>
            </x-card>
        @empty
            <p>No projects yet.</p>
        @endforelse
    </div>
@endsection
