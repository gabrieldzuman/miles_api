<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * Create a new middleware instance.
     * This constructor initializes the `except` array dynamically from configuration or logic.
     */
    public function __construct()
    {
        parent::__construct(app());
        
        $this->except = $this->getCsrfExemptUris();
        
        $this->logCsrfExemptUris();
    }

    /**
     * Retrieve the URIs to be excluded from CSRF protection.
     *
     * @return array<int, string>
     */
    protected function getCsrfExemptUris()
    {
        $exemptUris = Config::get('csrf.exempt_uris', []);
        if ($this->shouldExcludeApiRoutes()) {
            $exemptUris = array_merge($exemptUris, $this->getApiRoutes());
        }

        return $exemptUris;
    }

    /**
     * Determine whether to exclude API routes from CSRF verification.
     *
     * @return bool
     */
    protected function shouldExcludeApiRoutes()
    {
        return app()->environment('production') || auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get API routes that should be excluded from CSRF protection.
     *
     * @return array<int, string>
     */
    protected function getApiRoutes()
    {
        return [
            'payment/notify',
        ];
    }

    /**
     * Log the URIs excluded from CSRF verification.
     *
     * @return void
     */
    protected function logCsrfExemptUris()
    {
        Log::info('CSRF exemption list', [
            'exempt_uris' => $this->except,
        ]);
    }

    /**
     * Override tokensMatch to allow more control over CSRF validation process.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        if ($this->isTokenCheckBypassed($request)) {
            return true; 
        }
        return parent::tokensMatch($request);
    }

    /**
     * Determine if the CSRF token check should be bypassed for specific requests.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isTokenCheckBypassed($request)
    {
        return in_array($request->ip(), Config::get('trusted_services_ips', []));
    }
}
