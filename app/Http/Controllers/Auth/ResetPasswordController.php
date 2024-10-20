<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

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
     * Validate the password reset request.
     *
     * @param  Request  $request
     * @return ValidatorContract
     */
    protected function validateReset(Request $request): ValidatorContract
    {
        return $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
            ],
        ], [
            'password.regex' => 'The password must include at least one uppercase letter, one number, and one special character.',
        ]);
    }

    /**
     * Handle a password reset request for the application.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function reset(Request $request): RedirectResponse
    {
        $this->validateReset($request);

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return RedirectResponse
     */
    protected function sendResetResponse(Request $request, string $response): RedirectResponse
    {
        return redirect($this->redirectPath())
            ->with('status', trans($response))
            ->with('message', 'Your password has been successfully reset.');
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, string $response): RedirectResponse
    {
        return back()->withErrors([
            'email' => trans($response),
        ])->with('message', 'Password reset failed. Please try again.');
    }
}
