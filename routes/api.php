<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MobileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Public routes for mobile app (no authentication required for breeds)
Route::get('/breeds', [MobileController::class, 'getBreeds']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Mobile app routes
    Route::post('/emotion-detection', [MobileController::class, 'saveEmotionDetection']);

    // Audio file routes
    Route::prefix('audio')->group(function () {
        // History of audio files
        Route::get('/history', [AudioController::class, 'getHistory']);
        Route::post('/history', [AudioController::class, 'addToHistory']);
        Route::delete('/clear-history', [AudioController::class, 'clearHistory']);

        // Recent audio files
        Route::get('/recent', [AudioController::class, 'getRecent']);
        Route::post('/recent', [AudioController::class, 'updateRecent']);

        // Favorites
        Route::get('/favorites', [AudioController::class, 'getFavorites']);

        // Audio conversion
        Route::post('/convert-m4a-to-wav', [AudioController::class, 'convertM4aToWav']);

        // Individual audio file operations
        Route::get('/{id}', [AudioController::class, 'show']);
        Route::delete('/{id}', [AudioController::class, 'destroy']);
    });
});

// Admin Dashboard API Routes (protected by admin authentication)
Route::middleware('auth.admin.api')->prefix('admin')->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
    Route::get('/users', [DashboardController::class, 'getUserManagementData']);
    Route::get('/audio-files', [DashboardController::class, 'getAudioFilesData']);
});
