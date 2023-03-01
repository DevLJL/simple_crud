<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * Auth (Autenticação)
 */
Route::group(
  [], 
  function () {
    Route::post("register",  [AuthController::class, 'register'])->name("auth-controller.register");
    Route::post("login",  [AuthController::class, 'login'])->name("auth-controller.login");
  }
);

Route::group(
  ['middleware' => ['auth:sanctum']], 
  function () {
    Route::get("user",  [AuthController::class, 'user'])->name("auth-controller.user");
    Route::post("logout",  [AuthController::class, 'logout'])->name("auth-controller.logout");
  }
);