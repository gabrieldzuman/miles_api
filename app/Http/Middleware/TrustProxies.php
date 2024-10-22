<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * It supports dynamic configuration and environment-specific settings.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers;

    /**
     * TrustProxies constructor.
     * Initialize trusted proxies and headers with dynamic configuration and logging.
     */
    public function __construct()
    {
        $this->proxies = $this->getTrustedProxies();

        $this->headers = $this->getProxyHeaders();

        $this->logTrustedProxies();
    }

    /**
     * Get trusted proxies from configuration or environment variables.
     *
     * @return array<int, string>|string|null
     */
    protected function getTrustedProxies()
    {
        $proxies = Config::get('trusted_proxies', '*');
        return $this->validateProxies($proxies);
    }

    /**
     * Get proxy headers based on environment or defaults.
     * The method can be extended to allow dynamic header configuration.
     *
     * @return int
     */
    protected function getProxyHeaders()
    {
        return Config::get('proxy_headers', 
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );
    }

    /**
     * Validate the trusted proxies array to ensure no invalid entries.
     *
     * @param array<int, string>|string|null $proxies
     * @return array<int, string>|string|null
     */
    protected function validateProxies($proxies)
    {
        if (is_array($proxies)) {
            return array_filter($proxies, function ($proxy) {
                return filter_var($proxy, FILTER_VALIDATE_IP) || $this->isValidHostname($proxy);
            });
        }
        return $proxies;
    }

    /**
     * Validate whether the string is a valid hostname.
     *
     * @param string $hostname
     * @return bool
     */
    protected function isValidHostname($hostname)
    {
        return preg_match('/^[a-zA-Z0-9.-]+$/', $hostname);
    }

    /**
     * Log the trusted proxies and headers for debugging purposes.
     *
     * @return void
     */
    protected function logTrustedProxies()
    {
        Log::info('Trusted proxies configuration', [
            'proxies' => $this->proxies,
            'headers' => $this->headers,
        ]);
    }
}
