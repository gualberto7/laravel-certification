<?php

use App\Models\Tag;
use App\Models\User;

test('a project relates to its owner, tasks and tags', function () {
    $user = User::factory()->create();
    $project = $user->projects()->create(
        [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]
    );
    $task = $project->tasks()->create(
        [
            'title' => 'Test Task',
            'description' => 'Test Task Description',
        ]
    );
    $tag = Tag::factory()->create();
    $task->tags()->attach($tag);

    expect($project->user->is($user))->toBeTrue();
    expect($project->tasks)->toHaveCount(1);
    expect($task->tags->first()->is($tag))->toBeTrue();
    expect($tag->tasks->first()->is($task))->toBeTrue();
});
