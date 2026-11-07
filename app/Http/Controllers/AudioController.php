<?php

namespace App\Http\Controllers;

use App\Models\AudioFile;
use App\Services\AudioConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AudioController extends Controller
{
    /**
     * Get audio file history for authenticated user
     * GET /api/audio/history
     */
    public function getHistory(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            $query = $user->audioFiles();

            // Apply search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('original_filename', 'like', "%{$search}%")
                        ->orWhere('transcription', 'like', "%{$search}%");
                });
            }

            // Apply sorting
            $allowedSortFields = ['created_at', 'last_played_at', 'play_count', 'duration', 'file_size'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $audioFiles = $query->paginate($perPage);

            return response()->json([
                'status' => true,
                'message' => 'Audio history retrieved successfully',
                'data' => [
                    'audio_files' => $audioFiles->items(),
                    'pagination' => [
                        'current_page' => $audioFiles->currentPage(),
                        'last_page' => $audioFiles->lastPage(),
                        'per_page' => $audioFiles->perPage(),
                        'total' => $audioFiles->total()
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve audio history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add audio file to history
     * POST /api/audio/history
     */
    public function addToHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:mp3,wav,m4a,aac,ogg|max:102400', // 100MB max
            'transcription' => 'nullable|string|max:5000',
            'metadata' => 'nullable|json',
            'duration' => 'nullable|numeric|min:0',
        ], [
            'file.required' => 'Audio file is required',
            'file.file' => 'Upload must be a valid file',
            'file.mimes' => 'File must be an audio file (mp3, wav, m4a, aac, ogg)',
            'file.max' => 'File size must not exceed 100MB',
            'transcription.max' => 'Transcription must not exceed 5000 characters',
            'metadata.json' => 'Metadata must be valid JSON',
            'duration.numeric' => 'Duration must be a number',
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
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $uniqueId = Str::uuid();

            // Store file in public storage
            $storedFilename = $uniqueId . '.' . $extension;
            $storedFilePath = 'audio/' . $user->id . '/' . $storedFilename;
            Storage::disk('public')->put($storedFilePath, file_get_contents($file->getRealPath()));

            // Parse optional fields
            $transcription = $request->input('transcription');
            $duration = $request->input('duration');
            $metadata = [];

            if ($request->has('metadata')) {
                $metadataInput = $request->input('metadata');
                if (is_string($metadataInput)) {
                    $metadata = json_decode($metadataInput, true) ?? [];
                } else {
                    $metadata = $metadataInput ?? [];
                }
            }

            // Create audio file record
            $audioFile = AudioFile::create([
                'user_id' => $user->id,
                'filename' => $storedFilename,
                'original_filename' => $originalFilename,
                'file_path' => $storedFilePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'duration' => $duration,
                'transcription' => $transcription,
                'metadata' => $metadata
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Audio file added to history successfully',
                'data' => [
                    'audio_file' => [
                        'id' => $audioFile->id,
                        'filename' => $audioFile->filename,
                        'original_filename' => $audioFile->original_filename,
                        'file_url' => $audioFile->file_url,
                        'file_size' => $audioFile->file_size,
                        'created_at' => $audioFile->created_at
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add audio file to history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent audio files
     * GET /api/audio/recent
     */
    public function getRecent(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->get('per_page', 10);

            $audioFiles = $user->audioFiles()
                ->orderBy('last_played_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'message' => 'Recent audio files retrieved successfully',
                'data' => [
                    'audio_files' => $audioFiles->items(),
                    'pagination' => [
                        'current_page' => $audioFiles->currentPage(),
                        'last_page' => $audioFiles->lastPage(),
                        'per_page' => $audioFiles->perPage(),
                        'total' => $audioFiles->total()
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve recent audio files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update recent audio files
     * POST /api/audio/recent
     */
    public function updateRecent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audio_file_id' => 'required|exists:audio_files,id',
        ], [
            'audio_file_id.required' => 'Audio file ID is required',
            'audio_file_id.exists' => 'Audio file not found',
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
            $audioFile = AudioFile::where('id', $request->audio_file_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Update last played timestamp and increment play count
            $audioFile->update([
                'last_played_at' => now(),
                'play_count' => $audioFile->play_count + 1
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Recent audio files updated successfully',
                'data' => [
                    'audio_file' => [
                        'id' => $audioFile->id,
                        'last_played_at' => $audioFile->last_played_at,
                        'play_count' => $audioFile->play_count
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update recent audio files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific audio file
     * GET /api/audio/{id}
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $audioFile = AudioFile::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            return response()->json([
                'status' => true,
                'message' => 'Audio file retrieved successfully',
                'data' => [
                    'audio_file' => [
                        'id' => $audioFile->id,
                        'filename' => $audioFile->filename,
                        'original_filename' => $audioFile->original_filename,
                        'file_url' => $audioFile->file_url,
                        'file_type' => $audioFile->file_type,
                        'file_size' => $audioFile->file_size,
                        'duration' => $audioFile->duration,
                        'transcription' => $audioFile->transcription,
                        'metadata' => $audioFile->metadata,
                        'is_favorite' => $audioFile->is_favorite,
                        'play_count' => $audioFile->play_count,
                        'last_played_at' => $audioFile->last_played_at,
                        'created_at' => $audioFile->created_at,
                        'updated_at' => $audioFile->updated_at
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Audio file not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Delete an audio file
     * DELETE /api/audio/{id}
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $audioFile = AudioFile::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Delete file from storage
            if (Storage::disk('public')->exists($audioFile->file_path)) {
                Storage::disk('public')->delete($audioFile->file_path);
            }

            // Delete database record
            $audioFile->delete();

            return response()->json([
                'status' => true,
                'message' => 'Audio file deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete audio file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get favorite audio files
     * GET /api/audio/favorites
     */
    public function getFavorites(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->get('per_page', 15);

            $audioFiles = $user->audioFiles()
                ->where('is_favorite', true)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'message' => 'Favorite audio files retrieved successfully',
                'data' => [
                    'audio_files' => $audioFiles->items(),
                    'pagination' => [
                        'current_page' => $audioFiles->currentPage(),
                        'last_page' => $audioFiles->lastPage(),
                        'per_page' => $audioFiles->perPage(),
                        'total' => $audioFiles->total()
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve favorite audio files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear audio history
     * DELETE /api/audio/clear-history
     */
    public function clearHistory(Request $request)
    {
        try {
            $user = $request->user();

            // Get all audio files for the user
            $audioFiles = $user->audioFiles()->get();
            $deletedCount = 0;
            $errors = [];

            foreach ($audioFiles as $audioFile) {
                try {
                    // Delete file from storage (only if file_path exists and is not null)
                    if ($audioFile->file_path && !empty($audioFile->file_path)) {
                        // Skip if it's a mobile app local path (file:///)
                        if (!str_starts_with($audioFile->file_path, 'file:///')) {
                            if (Storage::disk('public')->exists($audioFile->file_path)) {
                                Storage::disk('public')->delete($audioFile->file_path);
                            }
                        }
                    }

                    // Delete database record
                    $audioFile->delete();
                    $deletedCount++;
                } catch (\Exception $fileException) {
                    // Log error but continue with other files
                    Log::error('Error deleting audio file', [
                        'audio_file_id' => $audioFile->id,
                        'error' => $fileException->getMessage()
                    ]);
                    $errors[] = "Failed to delete file ID {$audioFile->id}: " . $fileException->getMessage();

                    // Still try to delete the database record even if file deletion failed
                    try {
                        $audioFile->delete();
                        $deletedCount++;
                    } catch (\Exception $dbException) {
                        Log::error('Error deleting audio file database record', [
                            'audio_file_id' => $audioFile->id,
                            'error' => $dbException->getMessage()
                        ]);
                    }
                }
            }

            $response = [
                'status' => true,
                'message' => 'Audio history cleared successfully',
                'data' => [
                    'deleted_count' => $deletedCount,
                    'message' => "Successfully deleted {$deletedCount} audio file(s)"
                ]
            ];

            // Include errors if any occurred
            if (!empty($errors)) {
                $response['data']['errors'] = $errors;
                $response['data']['warning'] = 'Some files could not be deleted from storage, but database records were removed';
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error('Failed to clear audio history', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to clear audio history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert M4A file to WAV format
     * POST /api/audio/convert-m4a-to-wav
     */
    public function convertM4aToWav(Request $request)
    {
        // Log incoming request
        Log::info('M4A to WAV conversion request', [
            'has_file' => $request->hasFile('file'),
            'content_type' => $request->header('Content-Type'),
        ]);

        // Log file details before validation
        if ($request->hasFile('file')) {
            $preFile = $request->file('file');
            Log::info('File received - pre-validation', [
                'original_name' => $preFile->getClientOriginalName(),
                'original_extension' => $preFile->getClientOriginalExtension(),
                'mime_type' => $preFile->getMimeType(),
                'client_mime_type' => $preFile->getClientMimeType(),
                'size' => $preFile->getSize(),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:102400', // 100MB max
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }

                    $extension = strtolower($value->getClientOriginalExtension());
                    $mimeType = $value->getMimeType();

                    // Accept files with .m4a extension OR M4A/MP4 MIME types
                    $validExtensions = ['m4a'];
                    $validMimeTypes = [
                        'audio/m4a',
                        'audio/x-m4a',
                        'audio/mp4a-latm',
                        'video/mp4', // M4A files are MP4 containers, often detected as video/mp4
                        'audio/mp4',
                    ];

                    $isValidExtension = in_array($extension, $validExtensions);
                    $isValidMimeType = in_array($mimeType, $validMimeTypes);

                    if (!$isValidExtension && !$isValidMimeType) {
                        $fail('File must be an M4A audio file. Received extension: ' . $extension . ', MIME type: ' . $mimeType);
                    }
                },
            ],
        ], [
            'file.required' => 'M4A audio file is required',
            'file.file' => 'Upload must be a valid file',
            'file.max' => 'File size must not exceed 100MB',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $file = $request->file('file');

            // Log file details after validation passes
            Log::info('File passed validation', [
                'original_name' => $file->getClientOriginalName(),
                'original_extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'client_mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            // Verify it's actually an M4A file
            $extension = strtolower($file->getClientOriginalExtension());
            Log::info('Extension check', [
                'detected_extension' => $extension,
                'expected' => 'm4a',
            ]);

            if ($extension !== 'm4a') {
                Log::error('Extension mismatch', [
                    'received' => $extension,
                    'filename' => $file->getClientOriginalName(),
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Invalid file format',
                    'error' => 'File must be in M4A format. Received: ' . $extension
                ], 422);
            }

            // Initialize conversion service
            $audioConversionService = new AudioConversionService();

            // Check if FFmpeg is available
            if (!$audioConversionService->isFFmpegAvailable()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Audio conversion service unavailable',
                    'error' => 'FFmpeg is not installed or not available on this system. Please contact the administrator.'
                ], 503);
            }

            // Generate unique filenames
            $originalFilename = $file->getClientOriginalName();
            $uniqueId = Str::uuid();
            $inputFilename = $uniqueId . '_input.m4a';
            $outputFilename = $uniqueId . '_converted.wav';

            // Create temporary directory
            $tempDir = public_path('temp/audio/' . $user->id);
            // Get canonical path for Windows compatibility
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $tempDir = realpath($tempDir) ?: $tempDir;

            $inputPath = $tempDir . DIRECTORY_SEPARATOR . $inputFilename;
            $outputPath = $tempDir . DIRECTORY_SEPARATOR . $outputFilename;

            // Normalize paths
            $inputPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $inputPath);
            $outputPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $outputPath);

            // Get file size before moving
            $originalFileSize = $file->getSize();

            // Store the uploaded file temporarily
            try {
                $file->move($tempDir, $inputFilename);

                // Verify the file was moved successfully
                if (!file_exists($inputPath)) {
                    Log::error('File move failed - file does not exist after move', [
                        'target_path' => $inputPath,
                        'temp_dir' => $tempDir
                    ]);

                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to process audio file',
                        'error' => 'File could not be moved to temporary directory. Please check directory permissions.'
                    ], 500);
                }
            } catch (\Exception $moveException) {
                Log::error('Exception during file move', [
                    'error' => $moveException->getMessage(),
                    'target_path' => $inputPath
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Failed to process audio file',
                    'error' => 'File move exception: ' . $moveException->getMessage()
                ], 500);
            }

            Log::info('Starting M4A to WAV conversion', [
                'input_path' => $inputPath,
                'output_path' => $outputPath,
                'original_filename' => $originalFilename
            ]);

            // Convert M4A to WAV
            $conversionResult = $audioConversionService->convertM4aToWav($inputPath, $outputPath);

            if (!$conversionResult['success']) {
                // Clean up input file
                if (file_exists($inputPath)) {
                    @unlink($inputPath);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Audio conversion failed',
                    'error' => $conversionResult['error'],
                    'debug_info' => [
                        'input_path' => $inputPath,
                        'output_path' => $outputPath,
                        'conversion_result' => $conversionResult
                    ]
                ], 500);
            }

            // Get audio information
            $audioInfo = $audioConversionService->getAudioInfo($conversionResult['output_path']);
            $duration = $audioInfo['success'] ? $audioInfo['duration'] : null;

            // Store the converted file permanently
            $storedFilename = $uniqueId . '.wav';
            $storedFilePath = 'audio/' . $user->id . '/' . $storedFilename;

            // Read the converted file and store it
            $convertedFileContent = file_get_contents($conversionResult['output_path']);
            Storage::disk('public')->put($storedFilePath, $convertedFileContent);

            // Create audio file record
            $audioFile = AudioFile::create([
                'user_id' => $user->id,
                'filename' => $storedFilename,
                'original_filename' => pathinfo($originalFilename, PATHINFO_FILENAME) . '.wav',
                'file_path' => $storedFilePath,
                'file_type' => 'audio/wav',
                'file_size' => $conversionResult['file_size'],
                'duration' => $duration,
                'transcription' => null,
                'metadata' => [
                    'original_format' => 'm4a',
                    'converted_to' => 'wav',
                    'conversion_command' => $conversionResult['command'] ?? null,
                    'original_file_size' => $originalFileSize,
                    'converted_file_size' => $conversionResult['file_size']
                ]
            ]);

            // Clean up temporary files
            if (file_exists($inputPath)) {
                @unlink($inputPath);
            }
            if (file_exists($conversionResult['output_path'])) {
                @unlink($conversionResult['output_path']);
            }

            Log::info('M4A to WAV conversion completed successfully', [
                'audio_file_id' => $audioFile->id,
                'stored_file_path' => $storedFilePath
            ]);

            return response()->json([
                'status' => true,
                'message' => 'M4A file converted to WAV successfully',
                'data' => [
                    'audio_file' => [
                        'id' => $audioFile->id,
                        'filename' => $audioFile->filename,
                        'original_filename' => $audioFile->original_filename,
                        'file_url' => $audioFile->file_url,
                        'file_type' => $audioFile->file_type,
                        'file_size' => $audioFile->file_size,
                        'duration' => $audioFile->duration,
                        'created_at' => $audioFile->created_at
                    ],
                    'conversion_info' => [
                        'original_format' => 'm4a',
                        'output_format' => 'wav',
                        'original_file_size' => $originalFileSize,
                        'converted_file_size' => $conversionResult['file_size'],
                        'compression_ratio' => $originalFileSize > 0
                            ? round(($conversionResult['file_size'] / $originalFileSize) * 100, 2)
                            : 0
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            // Clean up any temporary files in case of exception
            if (isset($inputPath) && file_exists($inputPath)) {
                @unlink($inputPath);
            }
            if (isset($outputPath) && file_exists($outputPath)) {
                @unlink($outputPath);
            }

            Log::error('Exception during M4A to WAV conversion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to convert audio file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
