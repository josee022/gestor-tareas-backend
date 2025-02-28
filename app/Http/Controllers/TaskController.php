<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Auth::user()->tasks()->with('tags')->get();
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:baja,media,alta,urgente',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pendiente,completada',
        ]);

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

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
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Tarea eliminada']);
    }

    public function togglePin($id)
    {
        $user = Auth::user();

        $task = Task::where('id', $id)->where('user_id', $user->id)->first();

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada o no pertenece al usuario'], 404);
        }

        $task->update(['is_pinned' => !$task->is_pinned]);

        return response()->json([
            'message' => $task->is_pinned ? 'Tarea fijada' : 'Tarea desfijada',
            'task' => $task,
        ]);
    }
}
