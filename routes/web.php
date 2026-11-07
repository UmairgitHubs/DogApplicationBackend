<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// General login route (redirects to admin login)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// General home route (redirects to admin dashboard)
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

// API health check route (no authentication required)
Route::get('/api/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/register', [App\Http\Controllers\AdminController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [App\Http\Controllers\AdminController::class, 'register']);
        Route::get('/login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\AdminController::class, 'login']);
    });

    // Success page (accessible after login/register)
    Route::get('/success', [App\Http\Controllers\AdminController::class, 'showSuccess'])->name('success');

    // Protected routes (authenticated)
    Route::middleware('auth.admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout');

        // User Management
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'userManagement'])->name('users');
        Route::get('/user-details', [App\Http\Controllers\AdminController::class, 'userDetails'])->name('user-details');
        Route::get('/users/{id}', [App\Http\Controllers\AdminController::class, 'getUser'])->name('users.get');
        Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
        Route::match(['put', 'patch'], '/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

        // Dog Breed Management
        Route::get('/breeds', [App\Http\Controllers\AdminController::class, 'breedManagement'])->name('breeds');
        Route::post('/breeds', [App\Http\Controllers\AdminController::class, 'storeBreed'])->name('breeds.store');
        Route::get('/breeds/{id}', [App\Http\Controllers\AdminController::class, 'getBreed'])->name('breeds.get');
        Route::match(['put', 'patch'], '/breeds/{id}', [App\Http\Controllers\AdminController::class, 'updateBreed'])->name('breeds.update');
        Route::delete('/breeds/{id}', [App\Http\Controllers\AdminController::class, 'deleteBreed'])->name('breeds.delete');

        // AI Emotions Monitoring
        Route::get('/emotions', [App\Http\Controllers\AdminController::class, 'emotionsMonitoring'])->name('emotions');

        // Voice Response Management
        Route::get('/voice-response', [App\Http\Controllers\AdminController::class, 'voiceResponseManagement'])->name('voice-response');

        // Audio Logs
        Route::get('/audio-logs', [App\Http\Controllers\AdminController::class, 'audioLogs'])->name('audio-logs');

        // History Module
        Route::get('/history', [App\Http\Controllers\AdminController::class, 'historyModule'])->name('history');

        // Multilingual Configuration
        Route::get('/multilingual', [App\Http\Controllers\AdminController::class, 'multilingualConfig'])->name('multilingual');

        // App Settings
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'appSettings'])->name('settings');

        // Payment & Subscription
        Route::get('/payments', [App\Http\Controllers\AdminController::class, 'payments'])->name('payments');

        // Advanced Analytics
        Route::get('/analytics', [App\Http\Controllers\AdminController::class, 'advancedAnalytics'])->name('analytics');

        // Sample Data Generation (for testing)
        Route::get('/generate-sample-data', [App\Http\Controllers\AdminController::class, 'generateSampleData'])->name('generate-sample-data');
    });
});

// Redirect root admin to login if not authenticated
Route::get('/admin', function () {
    return redirect()->route('admin.login');
});
