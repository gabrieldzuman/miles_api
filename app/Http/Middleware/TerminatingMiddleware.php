<?php

namespace Illuminate\Session\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class TerminatingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->logRequest($request);
        $this->applyRateLimiting($request);
        $response = $next($request);
        return $this->modifyResponse($response);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * This method is useful for long-running background tasks, 
     * like logging or cleaning up temporary data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate(Request $request, SymfonyResponse $response): void
    {
        try {
            $this->logResponse($request, $response);
            $this->cleanup($request, $response);
        } catch (Throwable $e) {
            Log::error('Error in TerminatingMiddleware: ' . $e->getMessage());
        }
    }

    /**
     * Log incoming request details for debugging or performance analysis.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function logRequest(Request $request): void
    {
        Log::info('Incoming request', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'headers' => $request->headers->all(),
        ]);
    }

    /**
     * Apply rate-limiting based on IP address or user information.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function applyRateLimiting(Request $request): void
    {
        $key = 'rate_limit:' . $request->ip();
        $requests = Cache::get($key, 0);
        
        if ($requests > 100) {
            abort(429, 'Too many requests');
        }

        Cache::increment($key, 1);
    }

    /**
     * Modify the response before sending it to the client, such as adding headers.
     *
     * @param \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    protected function modifyResponse(Response $response): Response
    {
        $response->headers->set('X-Custom-Header', 'TerminatingMiddleware');
        return $response;
    }

    /**
     * Log the response details, such as status code and response time.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    protected function logResponse(Request $request, SymfonyResponse $response): void
    {
        Log::info('Response sent', [
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'response_time' => microtime(true) - LARAVEL_START,
        ]);
    }

    /**
     * Clean up temporary data or perform background tasks after response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    protected function cleanup(Request $request, SymfonyResponse $response): void
    {
        Cache::forget('temporary_data:' . $request->ip());
    }
}
