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
        return response()->json(Folder::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = Folder::create([
            'name' => $request->name,
        ]);

        return response()->json($folder, 201);
    }

    public function update(Request $request, $id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return response()->json(['error' => 'Carpeta no encontrada'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update(['name' => $request->name]);

        return response()->json(['message' => 'Carpeta actualizada', 'folder' => $folder]);
    }

    public function show($id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return response()->json(['error' => 'Carpeta no encontrada'], 404);
        }

        return response()->json($folder->tasks);
    }

    public function destroy($id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return response()->json(['error' => 'Carpeta no encontrada'], 404);
        }

        Task::where('folder_id', $id)->update(['folder_id' => null]);

        $folder->delete();

        return response()->json(['message' => 'Carpeta eliminada correctamente']);
    }


    public function moveTask(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $task->update([
            'folder_id' => $request->folder_id,
        ]);

        return response()->json(['message' => 'Tarea movida con éxito', 'task' => $task]);
    }
}
