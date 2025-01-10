<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::patch('/tasks/{task}/incomplete', [TaskController::class, 'incomplete'])->name('task.incomplete');
    Route::patch('/tasks/{task}/archive', [TaskController::class, 'archive'])->name('task.archive');
    Route::patch('/tasks/{task}/restore', [TaskController::class, 'restore'])->name('task.restore');
    Route::patch('/tasks/{task}/tags', [TaskController::class, 'addTags'])->name('task.addtags');

    // Route::get('/tags', [TagController::class, 'index']);
    // Route::post('/tags', [TagController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});