<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @group Auth Endpoints
 */
class AuthenticateController extends Controller
{
    /**
     * Handle an incoming login request.
     *
     * @unauthenticated
     *
     * @bodyParam email string required Example: alejandrosram@outlook.com
     * @bodyParam password string required Example: 1234567890
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return response()->json([
            'token' => $user->createToken('auth')->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Handle an incoming logout request.
     *
     * @authenticated
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return response()->json(['logout' => $user->tokens()->delete()]);
    }
}
