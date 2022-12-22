<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    /**
     * Handle an incoming login request.
     *
     * @param  \App\Http\Requests\Api\Auth\LoginRequest  $request
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
     * Handle and incoming logout request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        /** @var \App\Models\User $user * */
        $user = Auth::user();

        return response()->json(['logout' => $user->token()->revoke()]);
    }
}
