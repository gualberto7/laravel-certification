<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

test('dashboard summary displays correct task counts', function () {
    $user = User::factory()->create();
    $project = $user->projects()->create(['name' => 'Test Project']);
    $overdueAt = now()->subDays(1);

    $project->tasks()->createMany([
        ['title' => 'Task 1', 'status' => 'pending', 'due_at' => now()->addDays(1)],
        ['title' => 'Task 1.1', 'status' => 'pending', 'due_at' => now()->addDays(2)],
        ['title' => 'Task 2', 'status' => 'in_progress', 'due_at' => $overdueAt],
        ['title' => 'Task 3', 'status' => 'completed', 'due_at' => now()->subDays(2)],
    ]);

    $otherUser = User::factory()->create();
    $otherProject = $otherUser->projects()->create(['name' => 'Other Project']);
    $otherProject->tasks()->createMany([
        ['title' => 'Other Task 1', 'status' => 'pending', 'due_at' => now()->addDays(1)],
        ['title' => 'Other Task 2', 'status' => 'in_progress', 'due_at' => $overdueAt],
    ]);

    $response = $this->actingAs($user)->get(route('home'));
    $response->assertSuccessful()
        ->assertViewIs('dashboard')
        ->assertSee($overdueAt->format('Y-m-d H:i'));

    $response->assertViewHas(
        'taskCounts',
        fn (array $counts): bool => $counts['pending'] === 2
            && $counts['in_progress'] === 1
            && $counts['completed'] === 1,
    );

    $response->assertViewHas(
        'overdueTasks',
        fn (array $overdue): bool => count($overdue) === 1
            && $overdue[0]['title'] === 'Task 2'
    );

    $response->assertViewHas('totalTasks', 4);
});

test('dashboard summary is cached by user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    Task::factory()->for($project)->create();

    $this->actingAs($user)->get(route('home'));

    expect(Cache::has($user->dashboardCacheKey()))->toBeTrue();
});

test('cache is invalidated after creating a task', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    Task::factory()->for($project)->create();

    $this->actingAs($user)->get(route('home'));

    expect(Cache::has($user->dashboardCacheKey()))->toBeTrue();

    $this->post(
        route('projects.tasks.store', $project),
        ['title' => 'new task', 'status' => 'pending', 'due_at' => now()->addDay()]
    );

    expect(Cache::has($user->dashboardCacheKey()))->toBeFalse();
});

test('tasks total updates successfully', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    Task::factory()->for($project)->create();

    $this->actingAs($user)->get(route('home'))
        ->assertViewHas('totalTasks', 1);

    expect(Cache::has($user->dashboardCacheKey()))->toBeTrue();

    $this->post(
        route('projects.tasks.store', $project),
        ['title' => 'new task', 'status' => 'pending', 'due_at' => now()->addDay()]
    );

    expect(Cache::has($user->dashboardCacheKey()))->toBeFalse();

    $this->get(route('home'))
        ->assertViewHas('totalTasks', 2);

    expect(Cache::has($user->dashboardCacheKey()))->toBeTrue();
});

test('cache keys are different for each users', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    Task::factory()->for($project)->create();

    $this->actingAs($user)->get(route('home'));

    expect(Cache::has($user->dashboardCacheKey()))->toBeTrue();

    $user1 = User::factory()->create();
    $project2 = Project::factory()->for($user1)->create();
    Task::factory()->for($project2)->create();

    $this->actingAs($user1)->get(route('home'));

    expect(Cache::has($user1->dashboardCacheKey()))->toBeTrue();

    expect($user->dashboardCacheKey())->not->toBe($user1->dashboardCacheKey());
});
