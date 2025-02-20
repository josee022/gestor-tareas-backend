<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Auth::user()->tasks;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'integer|min:1|max:5',
            'due_date' => 'nullable|date',
            'status' => 'in:pendiente,completada',
        ]);

        $task = Auth::user()->tasks()->create($request->all());

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'integer|min:1|max:5',
            'due_date' => 'nullable|date',
            'status' => 'in:pendiente,completada',
        ]);

        $task->update($request->all());

        return response()->json(['message' => 'Tarea actualizada', 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Tarea eliminada']);
    }
}
