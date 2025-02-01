<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register the user
     */
    public function register(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed'
            ]
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->email);

        return response()->json(['user' => $user, 'token' => $token->plainTextToken], 200);
    }

    /**
     * Login the user
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => [
                    'password' => ['Authentication failed.'],
                ]
            ], 422);
        }

        $token = $user->createToken($request->email);
        Cookie::queue('user_token', Str::random(12), 360);
        return response()->json(['user' => $user, 'token' => $token->plainTextToken], 200);
    }

    /**
     * Logout the user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], 204);
    }
}
