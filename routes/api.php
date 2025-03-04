<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

Route::get('/folders', [FolderController::class, 'index']);
Route::post('/folders', [FolderController::class, 'store']);
Route::put('/folders/{id}', [FolderController::class, 'update']);
Route::delete('/folders/{id}', [FolderController::class, 'destroy']);

Route::get('/folders/{id}/tasks', [FolderController::class, 'show']);
Route::put('/tasks/{id}/move', [FolderController::class, 'moveTask']);
Route::put('/tasks/{taskId}/remove-folder', [FolderController::class, 'removeTaskFromFolder']);

Route::put('/tasks/{id}/pin', [TaskController::class, 'togglePin']);

Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags', [TagController::class, 'store']);
Route::delete('/tags/{id}', [TagController::class, 'destroy']);

Route::post('/tasks/{taskId}/tags', [TagController::class, 'assignTagToTask']);
Route::delete('/tasks/{taskId}/tags/{tagId}', [TagController::class, 'removeTagFromTask']);

Route::get('/tasks/calendar', [TaskController::class, 'calendarTasks']);
