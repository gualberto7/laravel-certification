<?php

use App\Models\Project;

test('dashboard route returns correct response', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('Dashboard');
});

test('project show route returns correct response', function () {
    $project = Project::factory()->create();

    $response = $this->get(route('projects.show', $project));

    $response->assertStatus(200)
        ->assertJson([
            'id' => $project->id,
            'name' => $project->name,
        ]);
});

test('project show route returns 404 for non-existent project', function () {
    $response = $this->get(route('projects.show', ['project' => 999]));

    $response->assertStatus(404);
});

test('task show route returns correct response', function () {
    $project = Project::factory()->create();
    $task = $project->tasks()->create([
        'title' => 'Test Task',
    ]);

    $response = $this->get(route('projects.tasks.show', [$project, $task]));

    $response->assertStatus(200)
        ->assertJson([
            'project_id' => $project->id,
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
});
