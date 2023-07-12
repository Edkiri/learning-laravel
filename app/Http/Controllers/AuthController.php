<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $validData = $validator->validated();

        $newUser = User::create([
            'name' => $validData['name'],
            'surname' => $validData['surname'],
            'email' => $validData['email'],
            'password' => bcrypt($validData['password']),
            'role_id' => 2
        ]);

        $token = $newUser->createToken('apiToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $newUser,
                'token' => $token
            ]
        ], Response::HTTP_CREATED);
    }

    public function login(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'email' => 'required|email|',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $validData = $validator->validated();

            $user = User::where('email', $validData['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email or password incorrect'
                ], Response::HTTP_FORBIDDEN);
            }

            $token = $user->createToken('apiToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $token,
                    'user' => $user,
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
    public function logout(Request $req)
    {
        try {
            $headerToken = $req->bearerToken();
            $token = PersonalAccessToken::findToken($headerToken);
            $token->delete();

            return response()->json([
                'success' => true,
                'data' => []
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function profile()
    {
        try {
            $user = auth()->user();


            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
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

    public function deleteMyAccount() {
        try {
            $user = auth()->user();
            $userFound = User::find($user->id);
            $userFound->delete();


            return response()->json([
                'success' => true,
                'data' => []
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreAccount($userId) {
        try {
            User::withTrashed()->where('id', $userId)->restore();

            return response()->json([
                'success' => true,
                'data' => []
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting tasks' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
