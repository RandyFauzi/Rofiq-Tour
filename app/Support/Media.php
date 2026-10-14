<?php

namespace App\Support;

final class Media
{
    public static function url(?string $path): string
    {
        return asset($path ? (str_starts_with($path, 'images/') ? $path : 'storage/'.$path) : 'images/umroh/makkah-ihram.webp');
    }
}
