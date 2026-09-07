<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project): View
    {
        return view('tasks.create', [
            'project' => $project,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $task = $project->tasks()->create($request->validated());

        $task->tags()->sync($request->input('tags', []));

        Cache::forget($request->user()->dashboardCacheKey());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Task created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task): JsonResponse
    {
        return response()->json([
            'project_id' => $project->id,
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task): View
    {
        return view('tasks.edit', [
            'project' => $project,
            'task' => $task,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $task->update($request->validated());
        $task->tags()->sync($request->input('tags', []));

        Cache::forget($request->user()->dashboardCacheKey());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Task updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $task->delete();

        Cache::forget(auth()->user()->dashboardCacheKey());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Task deleted');
    }
}
