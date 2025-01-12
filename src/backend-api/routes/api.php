<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('/tasks/{task}/inprogress', [TaskController::class, 'inprogress'])->name('tasks.inprogress');
    Route::patch('/tasks/{task}/archive', [TaskController::class, 'archive'])->name('tasks.archive');
    Route::patch('/tasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::patch('/tasks/{task}/tags', [TaskController::class, 'addTags'])->name('tasks.addtags');
    Route::patch('/tasks/{task}/attachments', [TaskController::class, 'addAttachments'])->name('tasks.attachments');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});