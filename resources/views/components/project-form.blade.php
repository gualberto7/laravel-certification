@props([
    'project' => null,
])

<form
    method="POST"
    action="{{ $project ? route('projects.update', $project) : route('projects.store') }}"
    {{ $attributes->merge(['class' => 'grid max-w-xl gap-5']) }}
>
    @csrf

    @if ($project)
        @method('PATCH')
    @endif

    <div>
        <label for="name">Name</label>

        <input
            id="name"
            name="name"
            value="{{ old('name', $project?->name) }}"
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
        >{{ old('description', $project?->description) }}</textarea>

        @error('description')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button type="submit" class="rounded-lg bg-blue-500 px-2 py-1 text-white hover:bg-blue-600">
            {{ $project ? 'Update project' : 'Create project' }}
        </button>
    </div>
</form>
