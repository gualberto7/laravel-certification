<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $projects = $request->user()->projects()->with('tasks')->get();

        $tasks = $projects->pluck('tasks')->flatten(1);

        $taskCounts = $tasks->groupBy('status')->map->count();

        $overdueTasks = $tasks->filter(
            fn (Task $task): bool => $task->due_at?->isPast()
                && $task->status !== 'completed'
        );

        $totalTasks = $taskCounts->reduce(
            fn (int $total, int $count): int => $total + $count,
            0
        );

        return view('dashboard', compact('taskCounts', 'overdueTasks', 'totalTasks'));
    }
}
