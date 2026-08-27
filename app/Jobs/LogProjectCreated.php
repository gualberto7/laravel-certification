<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class LogProjectCreated implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Project $project)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Project created', [
            'project_id' => $this->project->id,
            'user_id' => $this->project->user_id,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Failed to log project creation', [
            'project_id' => $this->project->id,
            'error' => $exception?->getMessage(),
        ]);
    }
}
