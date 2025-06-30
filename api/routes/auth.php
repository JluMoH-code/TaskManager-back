<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [LoginController::class,"store"])->middleware("guest");