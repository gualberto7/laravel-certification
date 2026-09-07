<?php

use App\Jobs\LogProjectCreated;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('guests are redirected to login', function () {
    $this->get(route('projects.index'))
        ->assertRedirectToRoute('login');
});

test('a user can create a project', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Queue::fake();

    $response = $this->post(route('projects.store'), [
        'name' => 'Certification project',
        'description' => 'Practice application',
    ]);

    $project = Project::query()
        ->where('name', 'Certification project')
        ->firstOrFail();

    expect($project->user->is($user))->toBeTrue();

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Project created.');

    $this->assertModelExists($project);

    Queue::assertPushed(
        LogProjectCreated::class,
        fn (LogProjectCreated $job): bool => $job->project->is($project),
    );
});

test('create project page shows the correct form', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('projects.create'))
        ->assertSuccessful()
        ->assertViewIs('projects.create')
        ->assertSee('Create project')
        ->assertSee('Name')
        ->assertSee('Description')
        ->assertSee('Create project');
});

test('a project can be updated by its owner', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->patch(route('projects.update', $project), [
        'name' => 'Updated project name',
        'description' => 'Updated project description',
    ]);

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Project updated.');

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated project name',
        'description' => 'Updated project description',
    ]);
});

test('a project cannot be updated by a different user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $this->actingAs($user);

    $response = $this->patch(route('projects.update', $project), [
        'name' => 'Updated project name',
        'description' => 'Updated project description',
    ]);

    $response->assertForbidden();

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
        'name' => 'Updated project name',
        'description' => 'Updated project description',
    ]);
});

test('update project page show the project values', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create([
        'user_id' => $user->id,
        'name' => 'Original project name',
        'description' => 'Original project description',
    ]);

    $this->get(route('projects.edit', $project))
        ->assertSuccessful()
        ->assertViewIs('projects.edit')
        ->assertViewHas('project', $project)
        ->assertSee('Original project name')
        ->assertSee('Original project description')
        ->assertSee('Update project');
});

test('a user can see only their own projects', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Project::factory()->create([
        'user_id' => $user->id,
        'name' => 'User project',
    ]);

    Project::factory()->create([
        'name' => 'Other project',
    ]);

    $this->get(route('projects.index'))
        ->assertSuccessful()
        ->assertViewIs('projects.index')
        ->assertSee('User project')
        ->assertDontSee('Other project');
});

test('a user can delete their own project', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->delete(route('projects.destroy', $project));

    $response
        ->assertRedirectToRoute('projects.index')
        ->assertSessionHas('status', 'Project deleted.');

    $this->assertModelMissing($project);
});

test('a user cannot delete a project they do not own', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create();

    $response = $this->delete(route('projects.destroy', $project));

    $response->assertForbidden();

    $this->assertModelExists($project);
});
