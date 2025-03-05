<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::with('tags')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:baja,media,alta,urgente',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pendiente,completada',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        if ($task->user_id !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:baja,media,alta,urgente',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pendiente,completada',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);
        return response()->json(['message' => 'Tarea actualizada', 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Tarea eliminada']);
    }

    public function togglePin($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        $task->update(['is_pinned' => !$task->is_pinned]);

        return response()->json([
            'message' => $task->is_pinned ? 'Tarea fijada' : 'Tarea desfijada',
            'task' => $task,
        ]);
    }

    public function calendarTasks()
    {
        $tasks = Task::whereNotNull('due_date')
            ->select('id', 'title', 'due_date')
            ->get();

        return response()->json($tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->due_date,
                'end' => $task->due_date,
            ];
        }));
    }
}
