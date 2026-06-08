<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'original_filename',
    'file_path',
    'analysis_json',
    'status',
    'error_message',
])]
class Analyse extends Model
{
    /** @use HasFactory<\Database\Factories\AnalyseFactory> */
    use HasFactory;

    protected $table = 'analyses';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'analysis_json' => 'array',
        ];
    }
}
