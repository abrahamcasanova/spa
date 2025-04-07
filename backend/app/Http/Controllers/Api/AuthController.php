<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Authenticate a user and generate access token
     *
     * @param LoginRequest $request The login request with validated credentials
     * @return \Illuminate\Http\JsonResponse Response with token and user data or error message
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user->status === Status::inactive()) {
            return response()->json(['error' => 'User is inactive'], Response::HTTP_UNAUTHORIZED);
        }
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Logout the authenticated user
     *
     * @param Request $request The HTTP request
     * @return \Illuminate\Http\JsonResponse Response with success message
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Session closed'], Response::HTTP_OK);
    }
}
