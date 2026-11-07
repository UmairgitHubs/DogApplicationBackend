<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.required' => 'Username is required',
            'username.string' => 'Username must be a text value',
            'username.max' => 'Username cannot exceed 255 characters',
            'username.unique' => 'This username is already taken. Please choose a different username.',
            'name.required' => 'Full name is required',
            'name.string' => 'Full name must be a text value',
            'name.max' => 'Full name cannot exceed 255 characters',
            'email.required' => 'Email address is required',
            'email.string' => 'Email must be a text value',
            'email.email' => 'Please enter a valid email address format',
            'email.max' => 'Email address cannot exceed 255 characters',
            'email.unique' => 'This email address is already registered. Please use a different email or try logging in.',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a text value',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match. Please make sure both passwords are identical.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified' => false,
            ]);

            // Generate and send OTP
            $otp = $this->otpService->generateOtp();
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            $emailSent = $this->otpService->sendOtp($user, $otp);

            if (!$emailSent) {
                // If email fails, delete the user and return error
                $user->delete();
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send verification email. Please try again.'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Registration successful. Please check your email for verification code.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified' => false,
                        'created_at' => $user->created_at,
                    ],
                    'requires_verification' => true
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            // Check if email is verified
            if (!$user->email_verified) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please verify your email before logging in',
                    'data' => [
                        'requires_verification' => true,
                        'user_id' => $user->id
                    ]
                ], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified' => $user->email_verified,
                        'created_at' => $user->created_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP and complete registration
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'otp' => 'required|string|size:4',
        ], [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User not found',
            'otp.required' => 'OTP is required',
            'otp.size' => 'OTP must be 4 digits',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);

            if ($user->email_verified) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email is already verified'
                ], 400);
            }

            if (!$this->otpService->verifyOtp($user, $request->otp)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired OTP'
                ], 400);
            }

            // Clear OTP and mark email as verified
            $this->otpService->clearOtp($user);

            // Create token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Email verified successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified' => true,
                        'created_at' => $user->created_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'OTP verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ], [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User not found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);

            if ($user->email_verified) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email is already verified'
                ], 400);
            }

            // Generate new OTP
            $otp = $this->otpService->generateOtp();
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            $emailSent = $this->otpService->sendOtp($user, $otp);

            if (!$emailSent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send verification email. Please try again.'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully. Please check your email.',
                'data' => [
                    'user_id' => $user->id
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to resend OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'status' => true,
                'message' => 'User profile retrieved successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve user profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forgot Password - Send OTP to user's email
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address format',
            'email.exists' => 'No account found with this email address',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            // Generate new OTP for password reset
            $otp = $this->otpService->generateOtp();
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(15), // 15 minutes for password reset
            ]);

            // Send OTP via email
            $emailSent = $this->otpService->sendPasswordResetOtp($user, $otp);

            if (!$emailSent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send password reset email. Please try again.'
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Password reset OTP sent successfully. Please check your email.',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'otp_expires_in_minutes' => 15
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to process password reset request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset Password - Reset password using OTP
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'otp' => 'required|string|size:4',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User not found',
            'otp.required' => 'OTP is required',
            'otp.size' => 'OTP must be 4 digits',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a text value',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match. Please make sure both passwords are identical.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);

            // Verify OTP
            if (!$this->otpService->verifyOtp($user, $request->otp)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired OTP'
                ], 400);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Clear OTP after successful password reset
            $this->otpService->clearOtp($user);

            // Revoke all existing tokens for security
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully. Please login with your new password.',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to reset password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:password|string',
            'password' => 'sometimes|required|string|min:8|confirmed',
        ], [
            'username.required' => 'Username is required',
            'username.string' => 'Username must be a text value',
            'username.max' => 'Username cannot exceed 255 characters',
            'username.unique' => 'This username is already taken. Please choose a different username.',
            'name.required' => 'Full name is required',
            'name.string' => 'Full name must be a text value',
            'name.max' => 'Full name cannot exceed 255 characters',
            'email.required' => 'Email address is required',
            'email.string' => 'Email must be a text value',
            'email.email' => 'Please enter a valid email address format',
            'email.max' => 'Email address cannot exceed 255 characters',
            'email.unique' => 'This email address is already registered. Please use a different email.',
            'current_password.required_with' => 'Current password is required when changing password',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a text value',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match. Please make sure both passwords are identical.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [];

            // Update username if provided
            if ($request->has('username')) {
                $updateData['username'] = $request->username;
            }

            // Update name if provided
            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }

            // Update email if provided
            if ($request->has('email')) {
                $updateData['email'] = $request->email;
                $updateData['email_verified'] = false; // Reset email verification status
            }

            // Update password if provided
            if ($request->has('password')) {
                // Verify current password
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Current password is incorrect'
                    ], 400);
                }

                $updateData['password'] = Hash::make($request->password);
            }

            // Update user data
            $user->update($updateData);

            // If email was changed, send verification OTP
            if ($request->has('email') && $request->email !== $user->getOriginal('email')) {
                $otp = $this->otpService->generateOtp();
                $user->update([
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(10),
                ]);

                $emailSent = $this->otpService->sendOtp($user, $otp);

                if (!$emailSent) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Profile updated but failed to send email verification. Please request a new verification email.'
                    ], 500);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified' => $user->email_verified,
                        'email_verified_at' => $user->email_verified_at,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                    'email_verification_required' => $request->has('email') && $request->email !== $user->getOriginal('email')
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
