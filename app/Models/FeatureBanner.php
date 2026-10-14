<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureBanner extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'eyebrow', 'description', 'image_path', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
