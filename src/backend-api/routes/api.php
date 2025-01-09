<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function() {
/*     Route::get('/tasks/{task}', [TaskController::class, 'show'])->can('viewOrModify', 'task');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->can('viewOrModify', 'task');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->can('viewOrModify', 'task');
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']); */

    Route::post('/logout', [AuthController::class, 'logout']);
});