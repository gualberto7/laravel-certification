@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
    <h1 class="text-3xl font-bold">
        Edit task for {{ $project->name }}
    </h1>

    <x-card class="mt-6">
        <x-task-form :project="$project" :task="$task" />
    </x-card>
@endsection