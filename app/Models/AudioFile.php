<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'duration',
        'transcription',
        'metadata',
        'is_favorite',
        'last_played_at',
        'play_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'is_favorite' => 'boolean',
        'last_played_at' => 'datetime',
        'file_size' => 'integer',
        'duration' => 'integer',
        'play_count' => 'integer',
    ];

    /**
     * Get the user that owns the audio file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get recent audio files (played in last 7 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('last_played_at', '>=', now()->subDays(7))
            ->orderBy('last_played_at', 'desc');
    }

    /**
     * Scope to get favorite audio files
     */
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true)
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Scope to get audio files by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the full URL for the audio file
     */
    public function getFileUrlAttribute(): ?string
    {
        // If file_path is null (emotion detection without audio file), return null
        if (!$this->file_path) {
            return null;
        }
        
        // If file_path is a mobile app local path, return null
        if (str_starts_with($this->file_path, 'file:///')) {
            return null;
        }
        
        // Generate URL for server-stored files
        return url('storage/' . $this->file_path);
    }
    
    /**
     * Check if this is an emotion detection record (no actual audio file)
     */
    public function isEmotionDetection(): bool
    {
        return $this->file_path === null || 
               ($this->metadata['detection_type'] ?? null) === 'emotion_detection' ||
               ($this->metadata['prediction_result']['source'] ?? null) === 'mobile_app';
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return 'Unknown';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
