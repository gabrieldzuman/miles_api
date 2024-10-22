<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\Http\Middleware\CheckClientCredentials as PassportClientCredentials;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Closure;
use Illuminate\Support\Facades\Route;
// use App\Traits\GetIp;

class CheckClientCredentials extends PassportClientCredentials
{
    // use GetIp;

    /**
     * Constructor to initialize the ResourceServer and TokenRepository.
     *
     * @param \League\OAuth2\Server\ResourceServer $server
     * @param \Laravel\Passport\TokenRepository $repository
     */
    public function __construct(ResourceServer $server, TokenRepository $repository)
    {
        parent::__construct($server, $repository);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed ...$scopes
     * @return mixed
     * @throws AuthenticationException
     * @throws \Laravel\Passport\Exceptions\MissingScopeException
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next, ...$scopes): mixed
    {
        $psr = (new PsrHttpFactory(
            new Psr17Factory,
            new Psr17Factory,
            new Psr17Factory,
            new Psr17Factory
        ))->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            $request->attributes->set('client_id', $psr->getAttribute('oauth_client_id'));
            $request->attributes->set('route_name', Route::currentRouteName());
            $request->attributes->set('request_payload', json_encode($request->all()));
        } catch (OAuthServerException $e) {
            logger()->error('OAuth error: ' . $e->getMessage());
            throw new AuthenticationException;
        }
        $this->validate($psr, $scopes);
        return $next($request);
    }
}
