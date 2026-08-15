<?php

use App\Models\Project;
use App\Models\Task;

test('dashboard route returns correct response', function () {
    $response = $this->get('/');

    $response->assertSuccessful()
        ->assertSee('Dashboard');
});

test('project show route returns correct response', function () {
    $project = Project::factory()->create([
        'name' => 'Certification project',
    ]);

    Task::factory()->for($project)->create([
        'title' => 'Learn Blade',
    ]);

    $this->get(route('projects.show', $project))
        ->assertSuccessful()
        ->assertViewIs('projects.show')
        ->assertViewHas('project')
        ->assertSee('Certification project')
        ->assertSee('Learn Blade');
});

test('project show route returns 404 for non-existent project', function () {
    $response = $this->get(route('projects.show', ['project' => 999]));

    $response->assertNotFound();
});

test('task show route returns correct response', function () {
    $project = Project::factory()->create();
    $task = $project->tasks()->create([
        'title' => 'Test Task',
    ]);

    $response = $this->get(route('projects.tasks.show', [$project, $task]));

    $response->assertSuccessful()
        ->assertJson([
            'project_id' => $project->id,
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
});

test('a task must belong to the project in the route', function () {
    $project = Project::factory()->create();
    $differentProject = Project::factory()->create();
    $task = Task::factory()->for($differentProject)->create();

    $this->get(route('projects.tasks.show', [$project, $task]))
        ->assertNotFound();
});

test('project index route returns correct response', function () {
    $project = Project::factory()->create();

    $this->get(route('projects.index'))
        ->assertSuccessful()
        ->assertViewIs('projects.index')
        ->assertViewHas('projects')
        ->assertSee($project->name);
});
