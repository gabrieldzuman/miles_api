<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function sendResetLinkEmail(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Customize the response after a successful reset link email is sent.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetLinkResponse(\Illuminate\Http\Request $request, string $response): RedirectResponse
    {
        return back()->with('status', trans($response));
    }

    /**
     * Customize the response after a failed attempt to send reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetLinkFailedResponse(\Illuminate\Http\Request $request, string $response): RedirectResponse
    {
        return back()->withErrors(['email' => trans($response)]);
    }
}
