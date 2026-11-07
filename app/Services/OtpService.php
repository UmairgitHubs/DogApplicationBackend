<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate a 4-digit OTP
     */
    public function generateOtp(): string
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP to user's email
     */
    public function sendOtp(User $user, string $otp): bool
    {
        try {
            Mail::send('emails.otp', [
                'user' => $user,
                'otp' => $otp
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Email Verification OTP - DogTranslation.com');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send password reset OTP to user's email
     */
    public function sendPasswordResetOtp(User $user, string $otp): bool
    {
        try {
            Mail::send('emails.password-reset-otp', [
                'user' => $user,
                'otp' => $otp
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Password Reset OTP - DogTranslation.com');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send password reset OTP email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify OTP for user
     */
    public function verifyOtp(User $user, string $otp): bool
    {
        if ($user->otp !== $otp) {
            return false;
        }

        if ($user->otp_expires_at && now()->isAfter($user->otp_expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Clear OTP after successful verification
     */
    public function clearOtp(User $user): void
    {
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);
    }
}
