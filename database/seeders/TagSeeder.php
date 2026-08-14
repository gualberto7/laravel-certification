<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::factory()->count(8)->create();

        Task::query()->each(function (Task $task) use ($tags): void {
            $task->tags()->attach(
                $tags->random(random_int(1, 3))->modelKeys()
            );
        });
    }
}
