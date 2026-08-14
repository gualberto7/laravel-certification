<?php

use App\Models\Task;

test('pending scope returns only pending tasks', function () {
    Task::factory()->create(
        [
            'status' => 'pending',
        ]
    );
    Task::factory()->create(
        [
            'status' => 'completed',
        ]
    );
    Task::factory()->create(
        [
            'status' => 'in_progress',
        ]
    );

    $pendingTasks = Task::pending()->get();

    expect($pendingTasks)->toHaveCount(1);
    expect($pendingTasks[0]->status)->toBe('pending');
});

test('overdue scope returns only overdue tasks', function () {
    Task::factory()->create(
        [
            'due_at' => now()->subDays(1),
            'status' => 'pending',
        ]
    );
    Task::factory()->create(
        [
            'due_at' => now()->subDays(1),
            'status' => 'in_progress',
        ]
    );
    Task::factory()->create(
        [
            'due_at' => now()->subDays(1),
            'status' => 'completed',
        ]
    );
    Task::factory()->create(
        [
            'due_at' => now()->addDays(1),
            'status' => 'pending',
        ]
    );

    $overdueTasks = Task::overdue()->get();

    expect($overdueTasks)->toHaveCount(2);
    expect($overdueTasks[0]->status)->toBe('pending');
    expect($overdueTasks[1]->status)->toBe('in_progress');
});
