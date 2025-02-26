<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return response()->json($user->folders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = Folder::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json($folder, 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $folder = Folder::where('id', $id)->where('user_id', $user->id)->first();

        if (!$folder) {
            return response()->json(['error' => 'Carpeta no encontrada o no pertenece al usuario'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update(['name' => $request->name]);

        return response()->json(['message' => 'Carpeta actualizada', 'folder' => $folder]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $folder = Folder::where('id', $id)->where('user_id', $user->id)->first();

        if (!$folder) {
            return response()->json(['error' => 'Carpeta no encontrada o no pertenece al usuario'], 404);
        }

        return response()->json([
            'folder' => $folder,
            'tasks' => $folder->tasks,
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $folder = Folder::where('id', $id)->where('user_id', $user->id)->first();

        if (!$folder) {
            return response()->json(['error' => 'Carpeta no encontrada o no pertenece al usuario'], 404);
        }

        Task::where('folder_id', $id)->update(['folder_id' => null]);
        $folder->delete();

        return response()->json(['message' => 'Carpeta eliminada correctamente']);
    }

    public function moveTask(Request $request, $taskId)
    {
        $user = Auth::user();

        $task = Task::where('id', $taskId)->where('user_id', $user->id)->first();

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada o no pertenece al usuario'], 404);
        }

        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($request->folder_id) {
            $folder = Folder::where('id', $request->folder_id)->where('user_id', $user->id)->first();
            if (!$folder) {
                return response()->json(['error' => 'La carpeta destino no pertenece al usuario'], 403);
            }
        }

        $task->update(['folder_id' => $request->folder_id]);

        return response()->json(['message' => 'Tarea movida con éxito', 'task' => $task]);
    }

    public function removeTaskFromFolder($taskId)
    {
        $user = Auth::user();

        $task = Task::where('id', $taskId)->where('user_id', $user->id)->first();

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada o no pertenece al usuario'], 404);
        }

        if (!$task->folder_id) {
            return response()->json(['message' => 'La tarea ya no está en ninguna carpeta'], 200);
        }

        $task->update(['folder_id' => null]);

        return response()->json(['message' => 'Tarea removida de la carpeta con éxito', 'task' => $task]);
    }
}
