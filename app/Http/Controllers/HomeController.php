<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * Apply 'auth' middleware to restrict access to authenticated users only.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->applyRateLimiting($request, 'home-page');
            $this->logMessage('User accessed home page.', ['user_id' => $request->user()->id]);
            return view('home');

        } catch (\Exception $e) {
            Log::error('An error occurred on the home page.', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null,
            ]);
            return $this->errorResponse('An error occurred while loading the home page.', 500);
        }
    }
}
