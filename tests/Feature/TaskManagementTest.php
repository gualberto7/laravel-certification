<?php

use App\Models\Project;
use App\Models\Task;

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

test('edit page displays the correct task', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->get(route('projects.tasks.edit', [$project, $task]));

    $response
        ->assertSuccessful()
        ->assertViewIs('tasks.edit')
        ->assertViewHas('project', $project)
        ->assertViewHas('task', $task)
        ->assertSee($task->title)
        ->assertSee($task->status);
});

test('a task can be updated', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->patch(
        route('projects.tasks.update', [$project, $task]),
        [
            'title' => 'New title',
            'status' => 'pending',
        ]
    );

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Task updated');

    expect($task->refresh())
        ->title->toBe('New title')
        ->status->toBe('pending');
});

test('a task can be deleted', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->delete(route('projects.tasks.destroy', [$project, $task]));

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Task deleted');

    $this->assertModelMissing($task);
});
