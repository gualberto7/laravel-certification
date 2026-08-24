@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-bold">Dashboard</h1>

    <p class="mt-2 text-slate-600">
        Laravel certification practice project.
    </p>

    <div class="mt-6 space-y-4">
        <x-card>
            <h2 class="text-xl font-bold">Task Summary</h2>
            <p>Total Tasks: {{ $totalTasks }}</p>
            <p>Pending Tasks: {{ $taskCounts->get('pending', 0) }}</p>
            <p>In Progress Tasks: {{ $taskCounts->get('in_progress', 0) }}</p>
            <p>Completed Tasks: {{ $taskCounts->get('completed', 0) }}</p>
        </x-card>

        @forelse ($overdueTasks as $task)
            <x-card class="mt-4">
                <h3 class="text-lg font-bold">{{ $task->title }}</h3>
                <p>Status: {{ ucfirst($task->status) }}</p>
                <p>Due Date: {{ $task->due_at ? $task->due_at->format('Y-m-d H:i') : 'N/A' }}</p>
            </x-card>
        @empty
            <p class="mt-4 text-slate-600">No overdue tasks.</p>
        @endforelse
    </div>
@endsection
