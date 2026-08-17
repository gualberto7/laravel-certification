@extends('layouts.app')

@section('title', 'Create task')

@section('content')
    <h1 class="text-3xl font-bold">
        Create task for {{ $project->name }}
    </h1>

    <x-card class="mt-6">
        <x-task-form :project="$project" />
    </x-card>
@endsection