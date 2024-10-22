<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class EnsureUserHasRole
{
    /**
     * Handle the incoming request, ensuring the user has the required role(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles  One or more roles to check for authorization.
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle(Request $request, Closure $next, string|array $roles): mixed
    {
        $roles = is_array($roles) ? $roles : [$roles];
        if (! $request->user() || ! $request->user()->hasAnyRole($roles)) {
            throw new AuthorizationException('You do not have the required authorization.');
        }
        return $next($request);
    }
}
