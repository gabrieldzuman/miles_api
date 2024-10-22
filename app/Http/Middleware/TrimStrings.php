<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Illuminate\Support\Facades\Log;

class TrimStrings extends Middleware
{
    /**
     * The attributes that should not be trimmed.
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Handle the incoming request and trim the strings.
     * You can extend the trimming logic or customize it here.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $this->logRequestBeforeTrimming($request);
        
        $this->sanitizeAttributes($request);

        return parent::handle($request, $next);
    }

    /**
     * Sanitize the input attributes by trimming and applying custom logic.
     * This allows for additional string operations beyond just trimming.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function sanitizeAttributes($request): void
    {
        $input = $request->all();
        foreach ($input as $key => $value) {
            if (!in_array($key, $this->except, true) && is_string($value)) {
                $request->merge([
                    $key => $this->trimAndSanitize($value)
                ]);
            }
        }
    }

    /**
     * Apply custom trimming and sanitization rules to the given string.
     * Here, you can add more rules (e.g., removing extra spaces between words, etc.)
     *
     * @param string $value
     * @return string
     */
    protected function trimAndSanitize(string $value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value));
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Log the incoming request's raw input before the trimming process for debugging.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function logRequestBeforeTrimming($request): void
    {
        Log::info('Raw request data before trimming', $request->all());
    }
}
