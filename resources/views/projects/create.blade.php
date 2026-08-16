@extends('layouts.app')

@section('title', 'Create project')

@section('content')
    <h1 class="text-3xl font-bold mb-3">Create project</h1>

    <x-card>
        <form
            method="POST"
            action="{{ route('projects.store') }}"
            class="mt-6 grid max-w-xl gap-5"
        >
            @csrf

            <div>
                <label for="user_id">Owner</label>

                <select id="user_id" name="user_id" class="rounded-lg p-1 border border-gray-300">
                    <option value="">Select an owner</option>

                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            @selected(old('user_id') == $user->id)
                        >
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                @error('user_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name">Name</label>

                <input
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="rounded-lg p-1 border border-gray-300"
                >

                @error('name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description">Description</label>

                <textarea
                    id="description"
                    name="description"
                    class="rounded-lg p-1 border border-gray-300"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="rounded-lg bg-blue-500 px-2 py-1 text-white hover:bg-blue-600">
                    Create project
                </button>
            </div>
        </form>
    </x-card>
@endsection
