@extends('layouts.app')

@section('title', 'Edit '.$project->name)

@section('content')
    <h1 class="text-3xl font-bold">Edit project</h1>
    
    <x-card class="mt-6">
        <x-project-form
            :project="$project"
        />
    </x-card>
@endsection
