<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $summary = Cache::remember(
            $user->dashboardCacheKey(),
            now()->addMinutes(10),
            fn (): array => $this->buildSummary($user),
        );

        return view('dashboard', $summary);
    }

    /**
     * @return array{
     *     taskCounts: array<string, int>,
     *     overdueTasks: array<int, array{
     *         title: string,
     *         status: string,
     *         due_at: string
     *     }>,
     *     totalTasks: int
     * }
     */
    private function buildSummary(User $user): array
    {
        $tasks = $user->projects()
            ->with('tasks')
            ->get()
            ->pluck('tasks')
            ->flatten(1);

        $taskCounts = $tasks
            ->groupBy('status')
            ->map
            ->count();

        $overdueTasks = $tasks
            ->filter(
                fn (Task $task): bool => $task->due_at?->isPast()
                    && $task->status !== 'completed',
            )
            ->map(fn (Task $task): array => [
                'title' => $task->title,
                'status' => $task->status,
                'due_at' => $task->due_at->format('Y-m-d H:i'),
            ])
            ->values();

        return [
            'taskCounts' => $taskCounts->all(),
            'overdueTasks' => $overdueTasks->all(),
            'totalTasks' => $taskCounts->sum(),
        ];
    }
}
