<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get("/login", function () {
    redirect("http://api.task-manager/api/documentation/#/auth");
})->name("login");

Route::post("/register", [LoginController::class, "store"])->middleware("guest");
Route::post("/login", [LoginController::class, "authenticate"])->middleware("guest");
Route::get("/logout", [LoginController::class, "destroy"])->middleware("auth");