<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Authenticated user',
                'payload' => $user
            ], 200);
        } catch (Throwable $e) {
            Log::error('User profile error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Could not fetch user profile.',
            ], 500);
        }
    }
}
