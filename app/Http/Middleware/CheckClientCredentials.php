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

    public function handle($request, Closure $next, ...$scopes)
    {
        $psr = (new PsrHttpFactory(
            new Psr17Factory,
            new Psr17Factory,
            new Psr17Factory,
            new Psr17Factory
        ))->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            // $request->request->set('client_id', $psr->getAttribute('oauth_client_id'));
            define('CLIENT_ID', $psr->getAttribute('oauth_client_id'));
            define('ROUTE_API', Route::currentRouteName());
            define('REQUEST_PAYLOAD', json_encode($request->toArray()));
            // define('ORIGIN_IP', $this->getIP());
			//$request->get('client_id');
        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }
        
        $this->validate($psr, $scopes);
        return $next($request);
    }
}

