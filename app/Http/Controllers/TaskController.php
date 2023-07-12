<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function getAllTasks()
    {
        try {
            $tasks = Task::with('user')->get();
            // $tasks = Task::where('description', 'like', '%' . 'aqui la descripcion' . '%')->get();

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

    public function createTask(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'description' => 'required|string',
                'user_id' => 'required'
            ], [
                'description.required' => 'description is a required field',
                'user_id' => 'error reading user_id'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors());
            }

            $validData = $validator->validated();
            $newTask = Task::create([
                'description' => $validData['description'],
                'user_id' => $validData['user_id'],
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'task' => $newTask
                ]
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks by user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateTask(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'description' => 'string',
                'status' => 'boolean',
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $validData = $validator->validated();

            $task = Task::find($validData['id']);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], Response::HTTP_NOT_FOUND);
            }

            if (isset($validData['description'])) {
                $task->description = $validData['description'];
            }
            if (isset($validData['status'])) {
                $task->status = $validData['status'];
            }

            $task->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'task' => $task
                ]
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks by user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteTask($taskId)
    {
        try {
            $task = Task::find($taskId);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'data' => []
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks by user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
