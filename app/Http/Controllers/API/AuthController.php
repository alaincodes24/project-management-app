<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // Check if email already exists
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email already taken.',
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role ?? 'user',
            ]);

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User registered',
                'payload' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 201);
        } catch (QueryException $qe) {
            Log::error('Duplicate registration attempt: ' . $qe->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'A user with this email already exists.',
            ], 409); // 409 Conflict
        } catch (Throwable $e) {
            Log::error('Register error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
            ], 500);
        }
    }


    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'payload' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 200);
        } catch (Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out'
            ]);
        } catch (Throwable $e) {
            Log::error('Logout error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed',
            ], 500);
        }
    }
}
