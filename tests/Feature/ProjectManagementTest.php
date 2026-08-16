<?php

use App\Models\Project;
use App\Models\User;

test('a user can create a project', function () {
    $user = User::factory()->create();

    $response = $this->post(route('projects.store'), [
        'user_id' => $user->id,
        'name' => 'Certification project',
        'description' => 'Practice application',
    ]);

    $project = Project::query()
        ->where('name', 'Certification project')
        ->firstOrFail();

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Project created.');

    $this->assertModelExists($project);
});

test('create project page shows the available owners', function () {
    User::factory()->create([
        'name' => 'Taylor Otwell',
    ]);

    $this->get(route('projects.create'))
        ->assertSuccessful()
        ->assertViewIs('projects.create')
        ->assertViewHas('users')
        ->assertSee('Taylor Otwell');
});

test('a project requires an existing owner and a name', function () {
    $this->post(route('projects.store'), [
        'user_id' => 999999,
        'name' => '',
        'description' => str_repeat('a', 2001),
    ])
        ->assertSessionHasErrors([
            'user_id',
            'name',
            'description',
        ]);

    expect(Project::query()->count())->toBe(0);
});
