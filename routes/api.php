<?php

use App\Http\Controllers\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user/{userId}', function ($userId) {
    // $user = DB::table('users')->where('id', $userId)->get();
    // $user = User::where('id', $userId)->get();
    $user = User::find($userId);

    return [
        'success' => true,
        'data' => [
            'user' => $user
        ]
    ];
}); {
}
Route::get('task', [TaskController::class, 'getAllTasks']);

Route::get('task/user/{userId}', [TaskController::class, 'getTasksByUser']);

Route::post('task', [TaskController::class, 'createTask']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
