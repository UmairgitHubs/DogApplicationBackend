<?php

/**
 * Test script for Audio Management APIs
 * This script tests the audio file history and recent audio functionality
 */

// Configuration
$baseUrl = 'http://localhost:8000/api';
$testToken = 'your-test-token-here'; // Replace with a valid token from login

echo "=== Audio Management API Test ===\n\n";

// Test 1: Get Audio History
echo "1. Testing Get Audio History API...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/audio/history?per_page=5');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $testToken,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

$historyResult = json_decode($response, true);

if ($historyResult && $historyResult['status']) {
    echo "✅ Get Audio History API working correctly\n";
} else {
    echo "❌ Get Audio History API test failed\n";
}

// Test 2: Get Recent Audio Files
echo "\n2. Testing Get Recent Audio Files API...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/audio/recent?per_page=5&days=7');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $testToken,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

$recentResult = json_decode($response, true);

if ($recentResult && $recentResult['status']) {
    echo "✅ Get Recent Audio Files API working correctly\n";
} else {
    echo "❌ Get Recent Audio Files API test failed\n";
}

// Test 3: Get Favorites
echo "\n3. Testing Get Favorites API...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/audio/favorites?per_page=5');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $testToken,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

$favoritesResult = json_decode($response, true);

if ($favoritesResult && $favoritesResult['status']) {
    echo "✅ Get Favorites API working correctly\n";
} else {
    echo "❌ Get Favorites API test failed\n";
}

// Test 4: Test Audio File Upload (if you have a test file)
echo "\n4. Testing Audio File Upload API...\n";
echo "Note: This test requires a valid audio file. Skipping for now.\n";
echo "To test upload, use the following curl command:\n";
echo "curl -X POST '$baseUrl/audio/history' \\\n";
echo "  -H 'Authorization: Bearer $testToken' \\\n";
echo "  -F 'file=@/path/to/your/audio.mp3' \\\n";
echo "  -F 'transcription=Test transcription' \\\n";
echo "  -F 'duration=120'\n\n";

// Test 5: Test Update Recent (if we have audio files)
if ($historyResult && $historyResult['status'] && !empty($historyResult['data']['audio_files'])) {
    $firstAudioFile = $historyResult['data']['audio_files'][0];
    $audioFileId = $firstAudioFile['id'];

    echo "5. Testing Update Recent Audio API...\n";
    $updateData = [
        'audio_file_id' => $audioFileId,
        'action' => 'played'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/audio/recent');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $testToken,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";

    $updateResult = json_decode($response, true);

    if ($updateResult && $updateResult['status']) {
        echo "✅ Update Recent Audio API working correctly\n";
    } else {
        echo "❌ Update Recent Audio API test failed\n";
    }
} else {
    echo "5. Skipping Update Recent Audio API test (no audio files found)\n";
}

echo "\n=== Test Complete ===\n";
echo "Note: To fully test the audio functionality, you need to:\n";
echo "1. Use a valid authentication token\n";
echo "2. Upload some audio files using the upload endpoint\n";
echo "3. Test the complete workflow\n";
