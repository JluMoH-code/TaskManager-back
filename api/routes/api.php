<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth');
Route::get('/tasks', [TaskController::class, 'getTasks'])->middleware('auth');