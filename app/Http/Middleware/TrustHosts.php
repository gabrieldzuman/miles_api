<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     * This version allows dynamic configuration of trusted hosts, logging, and validation for better security.
     *
     * @return array<int, string|null>
     */
    public function hosts()
    {
        $trustedHosts = [
            $this->allSubdomainsOfApplicationUrl(),
            'example.com',
            'api.example.com',
            '*.trustedapp.com',
        ];

        if ($envHosts = Config::get('app.trusted_hosts')) {
            $trustedHosts = array_merge($trustedHosts, $envHosts);
        }

        $this->logTrustedHosts($trustedHosts);
        return $this->validateTrustedHosts($trustedHosts);
    }

    /**
     * Log the trusted hosts to track the configuration in case of issues.
     *
     * @param array $trustedHosts
     * @return void
     */
    protected function logTrustedHosts(array $trustedHosts): void
    {
        Log::info('Trusted hosts configuration', ['hosts' => $trustedHosts]);
    }

    /**
     * Validate the trusted hosts to ensure there are no unsafe patterns.
     * You could add more complex validation logic here if necessary.
     *
     * @param array $trustedHosts
     * @return array
     */
    protected function validateTrustedHosts(array $trustedHosts): array
    {
        return array_filter($trustedHosts, function ($host) {
            return !is_null($host) && preg_match('/^[\w\*\.\-]+$/', $host);
        });
    }
}
