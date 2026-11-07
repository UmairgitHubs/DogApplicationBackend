<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AudioConversionService
{
    /**
     * Check if FFmpeg is available on the system
     */
    public function isFFmpegAvailable(): bool
    {
        $command = 'ffmpeg -version 2>&1';
        exec($command, $output, $returnCode);
        
        return $returnCode === 0;
    }

    /**
     * Convert M4A file to WAV format using FFmpeg
     * 
     * @param string $inputPath Path to the input M4A file
     * @param string $outputPath Path where the output WAV file should be saved
     * @return array Result array with success status, output path, file size, and error if any
     */
    public function convertM4aToWav(string $inputPath, string $outputPath): array
    {
        // Normalize paths for Windows compatibility
        $inputPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $inputPath);
        $outputPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $outputPath);

        // Check if FFmpeg is available
        if (!$this->isFFmpegAvailable()) {
            return [
                'success' => false,
                'error' => 'FFmpeg is not installed or not available on this system',
                'output_path' => null,
                'file_size' => 0
            ];
        }

        // Verify input file exists
        if (!file_exists($inputPath)) {
            return [
                'success' => false,
                'error' => 'Input file does not exist: ' . $inputPath,
                'output_path' => null,
                'file_size' => 0
            ];
        }

        // Ensure output directory exists
        $outputDir = dirname($outputPath);
        if (!is_dir($outputDir)) {
            if (!mkdir($outputDir, 0755, true)) {
                return [
                    'success' => false,
                    'error' => 'Failed to create output directory: ' . $outputDir,
                    'output_path' => null,
                    'file_size' => 0
                ];
            }
        }

        // Remove existing output file if it exists
        if (file_exists($outputPath)) {
            @unlink($outputPath);
        }

        // Build FFmpeg command for M4A to WAV conversion
        // Using pcm_s16le codec for WAV format (16-bit PCM, little-endian)
        // -ar 44100: Sample rate 44.1kHz
        // -ac 2: Stereo (2 channels)
        // -f wav: Output format WAV
        // -y: Overwrite output file if it exists
        $command = sprintf(
            'ffmpeg -i %s -acodec pcm_s16le -ar 44100 -ac 2 -f wav -y %s 2>&1',
            escapeshellarg($inputPath),
            escapeshellarg($outputPath)
        );

        Log::info('Executing FFmpeg conversion command', [
            'command' => $command,
            'input_path' => $inputPath,
            'output_path' => $outputPath
        ]);

        // Execute FFmpeg command
        exec($command, $output, $returnCode);

        $outputString = implode("\n", $output);

        // Check if conversion was successful
        if ($returnCode !== 0) {
            Log::error('FFmpeg conversion failed', [
                'return_code' => $returnCode,
                'output' => $outputString,
                'input_path' => $inputPath,
                'output_path' => $outputPath
            ]);

            return [
                'success' => false,
                'error' => 'FFmpeg conversion failed: ' . $outputString,
                'output_path' => null,
                'file_size' => 0,
                'command' => $command
            ];
        }

        // Verify output file was created
        $maxRetries = 5;
        $retryDelay = 200000; // 0.2 seconds in microseconds
        $fileExists = false;
        $fileSize = 0;

        for ($i = 0; $i < $maxRetries; $i++) {
            clearstatcache(true, $outputPath);
            
            // Try multiple path variations for Windows compatibility
            $pathVariations = [
                $outputPath,
                str_replace('/', '\\', $outputPath),
                str_replace('\\', '/', $outputPath),
                realpath($outputPath) ?: $outputPath
            ];
            $pathVariations = array_unique(array_filter($pathVariations));

            foreach ($pathVariations as $checkPath) {
                if (is_file($checkPath) && is_readable($checkPath)) {
                    $fileSize = filesize($checkPath);
                    if ($fileSize > 0) {
                        $fileExists = true;
                        $outputPath = $checkPath; // Use the path that worked
                        break 2; // Break out of both loops
                    }
                }
            }

            if ($i < $maxRetries - 1) {
                usleep($retryDelay);
            }
        }

        if (!$fileExists) {
            Log::error('Converted WAV file was not created after retries', [
                'output_path' => $outputPath,
                'input_path' => $inputPath,
                'return_code' => $returnCode,
                'ffmpeg_output' => $outputString
            ]);

            return [
                'success' => false,
                'error' => 'Converted WAV file was not created or is not accessible',
                'output_path' => null,
                'file_size' => 0,
                'command' => $command
            ];
        }

        Log::info('M4A to WAV conversion successful', [
            'input_path' => $inputPath,
            'output_path' => $outputPath,
            'file_size' => $fileSize
        ]);

        return [
            'success' => true,
            'output_path' => $outputPath,
            'file_size' => $fileSize,
            'command' => $command
        ];
    }

    /**
     * Get audio file information using FFprobe
     * 
     * @param string $filePath Path to the audio file
     * @return array Audio information including duration, file size, etc.
     */
    public function getAudioInfo(string $filePath): array
    {
        // Normalize path
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);

        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error' => 'File does not exist: ' . $filePath
            ];
        }

        // Check if FFprobe is available
        $command = 'ffprobe -version 2>&1';
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            return [
                'success' => false,
                'error' => 'FFprobe is not available',
                'file_size' => filesize($filePath)
            ];
        }

        // Get audio duration and other info
        $command = sprintf(
            'ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s 2>&1',
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnCode);
        $duration = $returnCode === 0 && !empty($output) ? floatval(trim($output[0])) : null;

        return [
            'success' => true,
            'duration' => $duration,
            'file_size' => filesize($filePath),
            'file_path' => $filePath
        ];
    }
}







