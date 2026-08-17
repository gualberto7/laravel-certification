<?php

use App\Models\Project;

test('create task page displays the project title', function () {
    $project = Project::factory()->create([
        'name' => 'Certification project',
    ]);

    $this->get(route('projects.tasks.create', $project))
        ->assertSuccessful()
        ->assertSee('Certification project');
});

test('validation errors are displayed when creating a task', function () {
    $project = Project::factory()->create();

    $this->post(route('projects.tasks.store', $project), [
        'title' => '',
        'description' => str_repeat('a', 2001),
        'status' => 'invalid_status',
        'due_at' => 'invalid_date',
    ])
        ->assertSessionHasErrors([
            'title',
            'description',
            'status',
            'due_at',
        ]);
});

test('a task can be created for a project', function () {
    $project = Project::factory()->create();

    $response = $this->post(route('projects.tasks.store', $project), [
        'title' => 'New task',
        'description' => 'Task description',
        'status' => 'pending',
    ]);

    $task = $project->tasks()->firstOrFail();

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Task created.');

    expect($task)
        ->title->toBe('New task')
        ->description->toBe('Task description')
        ->status->toBe('pending');
    expect($task->project->is($project))->toBeTrue();
});
