<?php

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;

test('guest users cannot access the tag management pages', function () {
    $tag = Tag::factory()->create();

    $this->get(route('tags.index'))->assertRedirect(route('login'));
    $this->get(route('tags.create'))->assertRedirect(route('login'));
    $this->post(route('tags.store'))->assertRedirect(route('login'));
    $this->get(route('tags.edit', $tag))->assertRedirect(route('login'));
    $this->put(route('tags.update', $tag))->assertRedirect(route('login'));
    $this->delete(route('tags.destroy', $tag))->assertRedirect(route('login'));
});

test('can list tags', function () {
    $this->actingAs(User::factory()->create());

    $tag = Tag::factory()->create();

    $this->get(route('tags.index'))
        ->assertOk()
        ->assertSee($tag->name);
});

test('can create a tag', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('tags.store'), [
        'name' => 'New Tag',
    ])->assertRedirect(route('tags.index'));

    $this->assertDatabaseHas('tags', [
        'name' => 'New Tag',
    ]);
});

test('cannot create a duplicate tag', function () {
    $this->actingAs(User::factory()->create());

    Tag::factory()->create([
        'name' => 'Duplicate Tag',
    ]);

    $this->post(route('tags.store'), [
        'name' => 'Duplicate Tag',
    ])->assertSessionHasErrors('name');
});

test('can edit a tag', function () {
    $this->actingAs(User::factory()->create());

    $tag = Tag::factory()->create();

    $this->put(route('tags.update', $tag), [
        'name' => 'Updated Tag',
    ])->assertRedirect(route('tags.index'));

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'Updated Tag',
    ]);
});

test('can delete a tag', function () {
    $this->actingAs(User::factory()->create());

    $tag = Tag::factory()->create();

    $this->delete(route('tags.destroy', $tag))
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);
});

test('deleted tag is removed from tasks', function () {
    $this->actingAs(User::factory()->create());

    $tag = Tag::factory()->create();
    $task = Task::factory()->create();
    $task->tags()->attach($tag);

    $this->delete(route('tags.destroy', $tag))
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);

    $this->assertDatabaseMissing('tag_task', [
        'tag_id' => $tag->id,
        'task_id' => $task->id,
    ]);
});
