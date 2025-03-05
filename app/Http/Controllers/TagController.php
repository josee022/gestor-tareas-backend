<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Task;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        return response()->json(Tag::where('user_id', $request->user_id)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,NULL,id,user_id,' . $request->user_id,
            'user_id' => 'required|exists:users,id'
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        return response()->json($tag, 201);
    }

    public function destroy(Request $request, $id)
    {
        $tag = Tag::where('id', $id)->where('user_id', $request->user_id)->first();

        if (!$tag) {
            return response()->json(['error' => 'Etiqueta no encontrada o no pertenece al usuario'], 404);
        }

        $tag->delete();
        return response()->json(['message' => 'Etiqueta eliminada correctamente']);
    }

    public function assignTagToTask(Request $request, $taskId)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::where('id', $taskId)->where('user_id', $request->user_id)->first();

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada o no pertenece al usuario'], 404);
        }

        $tag = Tag::where('id', $request->tag_id)->where('user_id', $request->user_id)->first();

        if (!$tag) {
            return response()->json(['error' => 'Etiqueta no encontrada o no pertenece al usuario'], 404);
        }

        $task->tags()->syncWithoutDetaching([$request->tag_id]);

        return response()->json(['message' => 'Etiqueta asignada correctamente']);
    }

    public function removeTagFromTask(Request $request, $taskId, $tagId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::where('id', $taskId)->where('user_id', $request->user_id)->first();

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada o no pertenece al usuario'], 404);
        }

        $tag = Tag::where('id', $tagId)->where('user_id', $request->user_id)->first();

        if (!$tag) {
            return response()->json(['error' => 'Etiqueta no encontrada o no pertenece al usuario'], 404);
        }

        $task->tags()->detach($tagId);

        return response()->json(['message' => 'Etiqueta eliminada correctamente']);
    }
}
