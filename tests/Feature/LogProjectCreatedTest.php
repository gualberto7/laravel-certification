<?php

use App\Jobs\LogProjectCreated;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

test('the job logs the created project', function () {
    Log::spy();

    $project = Project::factory()->create();

    (new LogProjectCreated($project))->handle();

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Project created', [
            'project_id' => $project->id,
            'user_id' => $project->user_id,
        ]);
});
