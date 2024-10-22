<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Handle successful responses for API requests.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data, $message = 'Success', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $statusCode);
    }

    /**
     * Handle error responses for API requests.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  array|null  $errors
     * @return JsonResponse
     */
    protected function errorResponse($message, $statusCode = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ], $statusCode);
    }

    /**
     * Log a message with optional context data.
     *
     * @param  string  $message
     * @param  array|null  $context
     * @param  string  $level
     * @return void
     */
    protected function logMessage(string $message, array $context = [], string $level = 'info'): void
    {
        Log::log($level, $message, $context);
    }

    /**
     * Override validation response for failed validation requests.
     *
     * @param  Request  $request
     * @param  array  $errors
     * @return JsonResponse
     */
    protected function invalidJson($request, array $errors): JsonResponse
    {
        return $this->errorResponse('Validation failed', 422, $errors);
    }

    /**
     * Apply rate limiting to controller actions.
     *
     * @param  Request  $request
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    protected function applyRateLimiting(Request $request, string $key, int $maxAttempts = 10, int $decayMinutes = 1): void
    {
        $key = $key . '|' . $request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            throw new \Illuminate\Http\Exceptions\ThrottleRequestsException(
                'Too many attempts, please try again in ' . $seconds . ' seconds.'
            );
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, $decayMinutes * 60);
    }
}
