<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportedLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'code',
        'status',
        'translation_file',
        'usage_count',
    ];

    protected $casts = [
        'status' => 'boolean',
        'usage_count' => 'integer',
    ];
}
