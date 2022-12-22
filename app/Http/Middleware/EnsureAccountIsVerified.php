<?php

namespace App\Http\Middleware;

use App\Exceptions\DefaultException;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureAccountIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail())
        ) {
            throw new DefaultException(__('Your account is not verified.'));
        }

        return $next($request);
    }
}
