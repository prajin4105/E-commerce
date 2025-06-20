<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\OtpService;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function sendOtp(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First verify if the credentials are valid
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        try {
            // Store credentials in session for later use
            session([
                'email' => $credentials['email'],
                'credentials' => $credentials,
                'remember' => $request->boolean('remember')
            ]);

            // Generate and send OTP
            $this->otpService->generateOtp($credentials['email']);
            
            // Redirect to OTP verification page
            return redirect()->route('verify.otp.form')->with('success', 'OTP has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send OTP. Please try again.',
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $email = session('email');
        $credentials = session('credentials');
        $remember = session('remember', false);

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please try logging in again.'
            ]);
        }

        if ($this->otpService->verifyOtp($email, $request->otp)) {
            // Clear OTP session data
            session()->forget(['email', 'credentials', 'remember']);
            // If already authenticated (registration flow), just redirect
            if (Auth::check()) {
                return redirect('/');
            }
            // Otherwise, log in using credentials (login flow)
            if ($credentials) {
                Auth::attempt($credentials, $remember);
            }
            return redirect('/');
        }

        return back()->withErrors([
            'otp' => 'Invalid OTP. Please try again.'
        ]);
    }

    public function resendOtp()
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update OTP in session
        session(['otp' => $otp]);

        // Send new OTP via email
        Mail::raw("Your new OTP for login is: {$otp}", function($message) {
            $message->to(session('email'))
                    ->subject('Your New Login OTP');
        });

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}
