<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Jobs\LogProjectCreated;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $projects = auth()->user()
            ->projects()
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = auth()->user()->projects()->create($request->validated());

        LogProjectCreated::dispatch($project);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Project created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project): View
    {
        $project->load('tasks.tags');

        $status = $request->query('status');
        if ($status) {
            $project->tasks = $project->tasks->filter(function ($task) use ($status) {
                return $task->status === $status;
            });
        }

        $tag = $request->query('tag') ? Tag::where('name', $request->query('tag'))->first() : null;
        if($tag) {
            $project->tasks = $project->tasks->filter(function ($task) use ($tag) {
                return $task->tags->contains($tag);
            });
        }

        return view('projects.show', [
            'project' => $project,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        abort_unless($project->user_id === auth()->id(), 403);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project deleted.');
    }
}
