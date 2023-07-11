<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function getAllTasks()
    {
        try {
            $tasks = Task::get();

            // throw new Exception("Error finding tasks.");

            return response()->json([
                'success' => true,
                'data' => [
                    'tasks' => $tasks
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTasksByUser($userId)
    {
        try {
            $tasks = Task::where('user_id', $userId)->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'tasks' => $tasks
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks by user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
