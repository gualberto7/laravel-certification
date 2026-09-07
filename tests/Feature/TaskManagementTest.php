<?php

use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;

test('create task page displays the project title', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create([
        'name' => 'Certification project',
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);
    $this->get(route('projects.tasks.create', $project))
        ->assertSuccessful()
        ->assertSee('Certification project');
});

test('validation errors are displayed when creating a task', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

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
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

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

test('a task cannot be created for a project by a different user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('projects.tasks.store', $project), [
        'title' => 'New task',
        'description' => 'Task description',
        'status' => 'pending',
    ]);

    $response->assertForbidden();
});

test('edit page displays the correct task', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);
    $this->actingAs($user);

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
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);
    $this->actingAs($user);

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
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);
    $this->actingAs($user);

    $response = $this->delete(route('projects.tasks.destroy', [$project, $task]));

    $response
        ->assertRedirectToRoute('projects.show', $project)
        ->assertSessionHas('status', 'Task deleted');

    $this->assertModelMissing($task);
});

test('a task cannot be deleted by a different user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);
    $this->actingAs($user);

    $response = $this->delete(route('projects.tasks.destroy', [$project, $task]));

    $response->assertForbidden();

    $this->assertModelExists($task);
});

test('create nad update task page has all tags', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $tags = Tag::factory(3)->create();
    $this->actingAs($user);

    $response = $this->get(route('projects.tasks.create', $project));

    foreach ($tags as $tag) {
        $response->assertSee($tag->name);
    }

    $task = Task::factory()->create(['project_id' => $project->id]);
    $response = $this->get(route('projects.tasks.edit', [$project, $task]));

    foreach ($tags as $tag) {
        $response->assertSee($tag->name);
    }
});

test('a task can be created with tags', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $tags = Tag::factory(3)->create();
    $this->actingAs($user);

    $this->post(route('projects.tasks.store', $project), [
        'title' => 'New task',
        'description' => 'Task description',
        'status' => 'pending',
        'tags' => $tags->pluck('id')->toArray(),
    ]);

    $task = Task::first();

    expect($task->tags->pluck('id')->toArray())->toEqualCanonicalizing(
        $tags->pluck('id')->toArray()
    );
});

test('can sync tags when updating a task', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);
    $tags = Tag::factory(3)->create();
    $task->tags()->attach($tags->pluck('id')->toArray());

    expect($task->tags()->count() === 3);

    $tag = Tag::factory()->create(['name' => 'Unrelated tag']);
    $this->actingAs($user);

    $this->patch(route('projects.tasks.update', [$project, $task]), [
        'title' => 'Updated task',
        'status' => 'pending',
        'tags' => [$tag->id],
    ]);

    expect($task->fresh()->tags()->count() === 1);
    expect($task->fresh()->tags()->first()->id)->toBe($tag->id);
});

test('can filter by status', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task1 = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'pending',
    ]);
    $task2 = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'completed',
    ]);
    $this->actingAs($user);

    $response = $this->get(route('projects.show', [
        'project' => $project,
        'status' => 'pending',
    ]));

    $response
        ->assertSuccessful()
        ->assertSee($task1->title)
        ->assertDontSee($task2->title);
});

test('can filter by tag', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $tag1 = Tag::factory()->create();
    $tag2 = Tag::factory()->create();
    $task1 = Task::factory()->create(['project_id' => $project->id]);
    $task2 = Task::factory()->create(['project_id' => $project->id]);
    $task1->tags()->attach($tag1);
    $task2->tags()->attach($tag2);
    $this->actingAs($user);

    $response = $this->get(route('projects.show', [
        'project' => $project,
        'tag' => $tag1->name,
    ]));

    $response
        ->assertSuccessful()
        ->assertSee($task1->title)
        ->assertDontSee($task2->title);
});

test('can filter by status and tag', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $tag1 = Tag::factory()->create();
    $tag2 = Tag::factory()->create();
    $task1 = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'pending',
    ]);
    $task2 = Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'completed',
    ]);
    $task1->tags()->attach($tag1);
    $task2->tags()->attach($tag2);
    $this->actingAs($user);

    $response = $this->get(route('projects.show', [
        'project' => $project,
        'status' => 'pending',
        'tag' => $tag1->name,
    ]));

    $response
        ->assertSuccessful()
        ->assertSee($task1->title)
        ->assertDontSee($task2->title);
});
