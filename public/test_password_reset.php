<?php

/**
 * Test script for Password Reset APIs
 * This script tests the forgot password and reset password functionality
 */

// Configuration
$baseUrl = 'http://localhost:8000/api';
$testEmail = 'test@example.com'; // Change this to a real email for testing

echo "=== Password Reset API Test ===\n\n";

// Test 1: Forgot Password
echo "1. Testing Forgot Password API...\n";
$forgotPasswordData = [
    'email' => $testEmail
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/forgot-password');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($forgotPasswordData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

$forgotPasswordResult = json_decode($response, true);

if ($forgotPasswordResult && $forgotPasswordResult['status']) {
    echo "✅ Forgot Password API working correctly\n";
    $userId = $forgotPasswordResult['data']['user_id'];

    // Test 2: Reset Password (with dummy OTP)
    echo "\n2. Testing Reset Password API...\n";
    $resetPasswordData = [
        'user_id' => $userId,
        'otp' => '1234', // This will likely fail as it's a dummy OTP
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/reset-password');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($resetPasswordData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";

    $resetPasswordResult = json_decode($response, true);

    if ($resetPasswordResult && !$resetPasswordResult['status'] && $resetPasswordResult['message'] === 'Invalid or expired OTP') {
        echo "✅ Reset Password API working correctly (correctly rejected invalid OTP)\n";
    } else {
        echo "❌ Reset Password API test failed\n";
    }
} else {
    echo "❌ Forgot Password API test failed\n";
}

echo "\n=== Test Complete ===\n";
echo "Note: To fully test the reset password functionality, you need to:\n";
echo "1. Use a real email address that exists in your database\n";
echo "2. Check the email for the actual OTP\n";
echo "3. Use the real OTP in the reset password request\n";
