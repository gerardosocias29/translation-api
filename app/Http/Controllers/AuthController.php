<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API endpoints for user authentication"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful login"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="429", description="Too many login attempts"),
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login:' . $request->email;

        // Check rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.'
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        // Check credentials
        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 600); // lockout for 600 seconds
            return response()->json([
                'message' => 'Invalid credentials'
            ], 422);
        }

        // Clear rate limiter on success
        RateLimiter::clear($key);

        // Return token + user info
        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Successful logout")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
