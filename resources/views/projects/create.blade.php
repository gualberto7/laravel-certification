@extends('layouts.app')

@section('title', 'Create project')

@section('content')
    <h1 class="text-3xl font-bold">Create project</h1>

    <x-card class="mt-6">
        <x-project-form />
    </x-card>
@endsection
