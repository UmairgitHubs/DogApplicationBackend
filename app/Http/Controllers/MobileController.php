<?php

namespace App\Http\Controllers;

use App\Models\DogBreed;
use App\Models\AudioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MobileController extends Controller
{
    /**
     * Get all dog breeds
     * GET /api/breeds
     */
    public function getBreeds(Request $request)
    {
        try {
            $breeds = DogBreed::orderBy('breed_name')->get()->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'breed_name' => $breed->breed_name,
                    'image_url' => $breed->image_url,
                    'image_path' => $breed->image_path,
                    'bark_characteristics' => $breed->bark_characteristics,
                    'response_voice_style' => $breed->response_voice_style,
                    'frequency' => $breed->frequency,
                    'analysis_count' => $breed->analysis_count,
                    'created_at' => $breed->created_at,
                    'updated_at' => $breed->updated_at,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Dog breeds retrieved successfully',
                'data' => [
                    'breeds' => $breeds
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching dog breeds: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve dog breeds',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save AI emotion detection data from mobile app
     * POST /api/emotion-detection
     * 
     * This endpoint only accepts emotion detection parameters (no audio files).
     * The data will be displayed on the admin dashboard.
     */
    public function saveEmotionDetection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'breed' => 'required|string|max:255',
            'emotion' => 'required|string|max:255',
            'confidence' => 'required|numeric|min:0|max:1',
            'language' => 'nullable|string|max:50',
            'transcription' => 'nullable|string|max:5000',
        ], [
            'breed.required' => 'Dog breed is required',
            'emotion.required' => 'Emotion is required',
            'confidence.required' => 'Confidence score is required',
            'confidence.numeric' => 'Confidence must be a number between 0 and 1',
            'confidence.min' => 'Confidence must be at least 0',
            'confidence.max' => 'Confidence must be at most 1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            // Prepare metadata structure for admin dashboard display
            $metadata = [
                'prediction_result' => [
                    'breed' => $request->breed,
                    'emotion' => $request->emotion,
                    'confidence' => floatval($request->confidence),
                    'language' => $request->language ?? 'English',
                    'processing_time' => 'N/A',
                    'source' => 'mobile_app'
                ],
                'detection_type' => 'emotion_detection',
                'has_audio_file' => false,
                'user_agent' => $request->header('User-Agent', 'Mobile App'),
                'timestamp' => now()->toISOString()
            ];

            // Create record to store emotion detection data (no actual audio file)
            // Using AudioFile model to maintain consistency with admin dashboard
            // This record only contains detection parameters, no audio file
            $audioFile = AudioFile::create([
                'user_id' => $user->id,
                'filename' => 'emotion_detection_' . time() . '.json',
                'original_filename' => 'emotion_detection.json',
                'file_path' => null, // No file path - this is just detection data, not an audio file
                'file_type' => 'application/json', // Mark as JSON data, not audio
                'file_size' => 0, // No file size since there's no actual file
                'duration' => null, // No duration since there's no audio file
                'transcription' => $request->transcription ?? null,
                'metadata' => $metadata,
                'is_favorite' => false,
                'play_count' => 0,
            ]);

            // Update dog breed analysis count
            $dogBreed = DogBreed::where('breed_name', $request->breed)->first();
            if ($dogBreed) {
                $dogBreed->increment('analysis_count');
            } else {
                // If breed doesn't exist, create it
                DogBreed::create([
                    'breed_name' => $request->breed,
                    'bark_characteristics' => 'Unknown',
                    'response_voice_style' => 'Unknown',
                    'frequency' => 'Unknown',
                    'analysis_count' => 1,
                ]);
            }

            Log::info('Emotion detection saved from mobile app', [
                'user_id' => $user->id,
                'breed' => $request->breed,
                'emotion' => $request->emotion,
                'confidence' => $request->confidence,
                'audio_file_id' => $audioFile->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Emotion detection data saved successfully',
                'data' => [
                    'detection_id' => $audioFile->id,
                    'breed' => $request->breed,
                    'emotion' => $request->emotion,
                    'confidence' => floatval($request->confidence),
                    'created_at' => $audioFile->created_at->toISOString()
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error saving emotion detection: ' . $e->getMessage(), [
                'user_id' => $request->user()->id ?? null,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save emotion detection data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



