<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Contracts\Auth\Guard;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended URL fails.
     *
     * @var string
     */
    protected string $redirectTo;

    /**
     * The auth guard implementation.
     *
     * @var Guard
     */
    protected Guard $auth;

    /**
     * Create a new controller instance.
     *
     * @param Guard $auth
     * @param string $redirectTo
     * @return void
     */
    public function __construct(Guard $auth, string $redirectTo = RouteServiceProvider::HOME)
    {
        $this->middleware('auth');
        $this->auth = $auth;
        $this->redirectTo = $redirectTo;
    }

    /**
     * Redirect user if password confirmation fails.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToFailedConfirmation()
    {
        return redirect($this->redirectTo)
            ->with('error', 'Password confirmation failed. Please try again.');
    }

    /**
     * Customize any other logic for password confirmation here, if necessary.
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmForm()
    {
        return view('auth.confirm-password');
    }
}
