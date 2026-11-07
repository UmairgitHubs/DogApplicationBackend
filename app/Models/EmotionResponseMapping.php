<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmotionResponseMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'emotion',
        'response_phrase',
    ];
}
