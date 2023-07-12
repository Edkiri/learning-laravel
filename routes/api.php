<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('task', [TaskController::class, 'getAllTasks']);
Route::get('task/user/{userId}', [TaskController::class, 'getTasksByUser']);
Route::post('task', [TaskController::class, 'createTask']);

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('user/profile', [AuthController::class, 'profile']);
