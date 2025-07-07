<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth');
Route::get('/tasks', [TaskController::class, 'getTasks'])->middleware('auth');
Route::post('/task', [TaskController::class, 'createTask'])->middleware('auth');
Route::put('/task/{id}', [TaskController::class, 'updateTask'])->middleware('auth');
Route::patch('/task/{id}/toggle', [TaskController::class, 'toggleActiveTask'])->middleware('auth');

Route::get('/tags', [TagController::class, 'getTags'])->middleware('auth');