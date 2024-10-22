<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * Cookies listed here will be stored as plain text, allowing for easier access
     * by third-party services or JavaScript-based client-side processing.
     *
     * @var array<int, string> $except
     */
    protected array $except = [
        'plain_user_preferences',
    ];
}
