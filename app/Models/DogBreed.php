<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogBreed extends Model
{
    use HasFactory;

    protected $fillable = [
        'breed_name',
        'image_path',
        'bark_characteristics',
        'response_voice_style',
        'frequency',
        'analysis_count',
    ];

    /**
     * Get the full URL for the breed image
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('images/dogs/' . $this->image_path);
        }
        return asset('images/dog-illustration.png'); // Default image
    }
}
