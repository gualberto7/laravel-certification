@props([
    'project',
    'task' => null,
])

<form
    method="POST"
    action="{{ $task ? route('projects.tasks.update', [$project, $task]) : route('projects.tasks.store', $project) }}"
    {{ $attributes->merge(['class' => 'grid max-w-xl gap-5']) }}
>
    @csrf

    @if ($task)
        @method('PATCH')
    @endif

    <div>
        <label for="title">Title</label>

        <input
            id="title"
            name="title"
            value="{{ old('title', $task?->title) }}"
            class="rounded-lg p-1 border border-gray-300"
        >

        @error('title')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description">Description</label>

        <textarea
            id="description"
            name="description"
            class="rounded-lg p-1 border border-gray-300"
        >{{ old('description', $task?->description) }}</textarea>

        @error('description')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status">Status</label>
        <select id="status" name="status" class="rounded-lg p-1 border border-gray-300">
            <option
                value="pending"
                @selected(old('status', $task?->status ?? 'pending') === 'pending')
            >
                Pending
            </option>

            <option
                value="in_progress"
                @selected(old('status', $task?->status ?? 'pending') === 'in_progress')
            >
                In progress
            </option>

            <option
                value="completed"
                @selected(old('status', $task?->status ?? 'pending') === 'completed')
            >
                Completed
            </option>
        </select>
        @error('status')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="due_at">Due Date</label>
        <input
            type="datetime-local"
            id="due_at"
            name="due_at"
            value="{{ old('due_at', $task?->due_at?->format('Y-m-d\TH:i')) }}"
            class="rounded-lg p-1 border border-gray-300"
        >
        @error('due_at')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button type="submit" class="rounded-lg bg-blue-500 px-2 py-1 text-white hover:bg-blue-600">
            {{ $task ? 'Update task' : 'Create task' }}
        </button>
    </div>
</form>