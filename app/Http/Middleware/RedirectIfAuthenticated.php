<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request, redirecting if the user is already authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards  The authentication guards to check.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$guards): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectAuthenticatedUser($guard);
            }
        }
        return $next($request);
    }

    /**
     * Determine the redirect path based on the authenticated guard.
     *
     * @param  string|null  $guard
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAuthenticatedUser(?string $guard): \Illuminate\Http\RedirectResponse
    {
        switch ($guard) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
            default:
                return redirect(RouteServiceProvider::HOME); 
        }
    }
}
