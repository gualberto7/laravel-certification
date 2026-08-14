<?php

use App\Models\Project;
use App\Models\Task;

test('dashboard route returns correct response', function () {
    $response = $this->get('/');

    $response->assertSuccessful()
        ->assertSee('Dashboard');
});

test('project show route returns correct response', function () {
    $project = Project::factory()->create();

    $response = $this->get(route('projects.show', $project));

    $response->assertSuccessful()
        ->assertJson([
            'id' => $project->id,
            'name' => $project->name,
        ]);
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
