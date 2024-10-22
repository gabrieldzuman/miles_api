<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * URIs added to this list will bypass the maintenance mode restrictions, 
     * allowing access to critical routes.
     *
     * @var array<int, string>
     */
    protected array $except = [
        'status',   
    ];

    /**
     * Determine if the request has a URI that should be reachable while in maintenance mode.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request): bool
    {
        return in_array($request->path(), $this->except);
    }
}
