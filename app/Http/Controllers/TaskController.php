<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->farm->tasks()
            ->with('assignee')
            ->latest()
            ->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        auth()->user()->farm->tasks()->create([
            ...$validated,
            'assigned_to' => $validated['assigned_to'] ?? auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
