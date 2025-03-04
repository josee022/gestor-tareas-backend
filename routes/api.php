<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Rutas para logueo, registros y seguridad
Route::middleware(['cors'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Ruta protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Rutas para acciones de tasks(notas)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Rutas para acciones de folders(carpetas)
    Route::get('/folders', [FolderController::class, 'index']);
    Route::post('/folders', [FolderController::class, 'store']);
    Route::put('/folders/{id}', [FolderController::class, 'update']);
    Route::delete('/folders/{id}', [FolderController::class, 'destroy']);

    // Rutas para ver los detalles de una carpeta, asignar nota a carpeta o sacar nota de carpeta
    Route::get('/folders/{id}/tasks', [FolderController::class, 'show']);
    Route::put('/tasks/{id}/move', [FolderController::class, 'moveTask']);
    Route::put('/tasks/{taskId}/remove-folder', [FolderController::class, 'removeTaskFromFolder']);

    // Ruta para fijar una nota
    Route::put('/tasks/{id}/pin', [TaskController::class, 'togglePin']);

    // Rutas para funciones de etiquetas
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::delete('/tags/{id}', [TagController::class, 'destroy']);

    // Rutas para asignar y quitar etiquetas
    Route::post('/tasks/{taskId}/tags', [TagController::class, 'assignTagToTask']);
    Route::delete('/tasks/{taskId}/tags/{tagId}', [TagController::class, 'removeTagFromTask']);

    // Ruta para calendario
    Route::get('/tasks/calendar', [TaskController::class, 'calendarTasks']);
});
