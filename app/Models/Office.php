<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['name', 'city', 'address', 'phone', 'map_url', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
