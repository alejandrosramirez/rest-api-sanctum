<?php

namespace App\Http\Controllers\ApiAdmin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAdmin\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    /**
     * Handle an incoming login request.
     *
     * @param  \App\Http\Requests\ApiAdmin\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('web_admin')->user();

        return response()->json([
            'token' => $admin->createToken('authAdmin')->plainTextToken,
            'admin' => $admin,
        ]);
    }

    /**
     * Handle and incoming logout request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('web_admin')->user();

        return response()->json(['logout' => $admin->token()->delete()]);
    }
}
