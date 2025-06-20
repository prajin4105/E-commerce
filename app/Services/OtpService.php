<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generateOtp(string $email): Otp
    {
        // Delete any existing unused OTPs for this email
        Otp::where('email', $email)
            ->where('is_used', false)
            ->delete();

        // Generate new OTP
        $otp = Otp::create([
            'email' => $email,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
            'is_used' => false
        ]);

        try {
            // Send OTP via email
            Mail::send('emails.otp', ['otp' => $otp->otp], function($message) use ($email) {
                $message->to($email)
                        ->subject('Your Login Verification Code');
            });

            Log::info('OTP sent successfully to: ' . $email);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP to: ' . $email . ' Error: ' . $e->getMessage());
            throw $e;
        }

        return $otp;
    }

    public function verifyOtp(string $email, string $otp): bool
    {
        $otpRecord = Otp::where('email', $email)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return false;
        }

        $otpRecord->update(['is_used' => true]);
        return true;
    }
} 