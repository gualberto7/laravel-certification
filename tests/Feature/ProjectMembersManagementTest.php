<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\QueryException;

use function PHPUnit\Framework\assertInstanceOf;

test('can add a member to a project', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);

    assertInstanceOf(User::class, $project->members()->first());
});

test('can read the role pivot attribute for a member', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);

    $member = $project->members()->where('user_id', $user->id)->first();

    expect($member->pivot->role)->toBe('editor');
});

test('can replace members with sync method', function () {
    $project = Project::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $project->members()->attach($user1, ['role' => 'editor']);
    $project->members()->sync([$user2->id => ['role' => 'viewer']]);

    assertInstanceOf(User::class, $project->members()->first());
    expect($project->members()->count())->toBe(1);
    expect($project->members()->first()->pivot->role)->toBe('viewer');
    expect($project->members()->first()->id)->toBe($user2->id);
    expect($project->members()->first()->id)->not->toBe($user1->id);
});

test('can remove a member from a project', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);
    $project->members()->detach($user);

    expect($project->members)->not->toContain($user);
    $this->assertModelExists($user);
    $this->assertDatabaseMissing('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);
});

test('cannot add the same member twice', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);

    expect(fn () => $project->members()->attach($user, ['role' => 'viewer']))
        ->toThrow(QueryException::class);
});

test('when a project is deleted, its members are also removed from the pivot table', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);
    $project->delete();

    expect($project->members()->count())->toBe(0);
    $this->assertModelExists($user);
});

test('can access from user to the projects they are a member of', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);

    assertInstanceOf(Project::class, $user->memberProjects()->first());
    expect($user->memberProjects()->count())->toBe(1);
    expect($user->memberProjects()->first()->pivot->role)->toBe('editor');
});

test('pivot has the necessary fields', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $project->members()->attach($user, ['role' => 'editor']);

    $member = $project->members()->where('user_id', $user->id)->first();

    expect($member->pivot)->toHaveKeys(['project_id', 'user_id', 'role', 'created_at', 'updated_at']);
});
