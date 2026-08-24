<?php

use App\Models\User;
use Illuminate\Support\Collection;

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
        fn (Collection $counts): bool => $counts->get('pending') === 2
            && $counts->get('in_progress') === 1
            && $counts->get('completed') === 1,
    );

    $response->assertViewHas(
        'overdueTasks',
        fn (Collection $overdue): bool => $overdue->count() === 1
            && $overdue->first()->title === 'Task 2'
            && $overdue->first()->due_at->isPast(),
    );

    $response->assertViewHas(
        'totalTasks',
        4,
    );
});
