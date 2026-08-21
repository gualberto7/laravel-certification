<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

test('dashboard route returns correct response', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->get('/');

    $response->assertSuccessful()
        ->assertSee('Dashboard');
});

test('project show route returns correct response', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create([
        'name' => 'Certification project',
        'user_id' => $user->id,
    ]);

    Task::factory()->create([
        'title' => 'Learn Blade',
        'project_id' => $project->id,
    ]);

    $this->actingAs($user);
    $this->get(route('projects.show', $project))
        ->assertSuccessful()
        ->assertViewIs('projects.show')
        ->assertViewHas('project')
        ->assertSee('Certification project')
        ->assertSee('Learn Blade');
});

test('a project cannot be accessed by a different user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('projects.show', $project));

    $response->assertForbidden();
});

test('project show route returns 404 for non-existent project', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->get(route('projects.show', ['project' => 999]));

    $response->assertNotFound();
});

test('task show route returns correct response', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = $project->tasks()->create([
        'title' => 'Test Task',
    ]);

    $this->actingAs($user);
    $response = $this->get(route('projects.tasks.show', [$project, $task]));

    $response->assertSuccessful()
        ->assertJson([
            'project_id' => $project->id,
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
});

test('a task must belong to the project in the route', function () {
    $this->actingAs(User::factory()->create());
    $project = Project::factory()->create();
    $differentProject = Project::factory()->create();
    $task = Task::factory()->for($differentProject)->create();

    $this->get(route('projects.tasks.show', [$project, $task]))
        ->assertNotFound();
});

test('project index route returns correct response', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $this->get(route('projects.index'))
        ->assertSuccessful()
        ->assertViewIs('projects.index')
        ->assertViewHas('projects')
        ->assertSee($project->name);
});
