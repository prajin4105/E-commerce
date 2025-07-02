<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the combined login and registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showCombinedForm()
    {
        return view('auth.combined');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            // Check if user exists
            $existingUser = \App\Models\User::where('email', $user->email)->first();
            
            if ($existingUser) {
                // Update google_id if not set
                if (!$existingUser->google_id) {
                    $existingUser->update(['google_id' => $user->id]);
                }
            } else {
                // Create new user
                $existingUser = \App\Models\User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt(Str::random(16))
                ]);
            }
            
            // Log the user in directly
            Auth::login($existingUser, true);
            
            // Redirect to home page
            return redirect('/');
            
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Error logging in with Google. Please try again.',
            ]);
        }
    }

    public function redirectToMicrosoft()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function handleMicrosoftCallback()
    {
        try {
            $user = Socialite::driver('microsoft')->user();
            
            // Check if user exists
            $existingUser = \App\Models\User::where('email', $user->email)->first();
            
            if ($existingUser) {
                // Update microsoft_id if not set
                if (!$existingUser->microsoft_id) {
                    $existingUser->update(['microsoft_id' => $user->id]);
                }
            } else {
                // Create new user
                $existingUser = \App\Models\User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'microsoft_id' => $user->id,
                    'password' => bcrypt(Str::random(16))
                ]);
            }
            
            // Log the user in directly
            Auth::login($existingUser, true);
            
            // Redirect to home page
            return redirect('/');
            
        } catch (\Exception $e) {
            \Log::error('Microsoft login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Error logging in with Microsoft. Please try again.',
            ]);
        }
    }
} 