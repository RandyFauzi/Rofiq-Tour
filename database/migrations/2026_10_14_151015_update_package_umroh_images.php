<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $images = [
        'umroh-plus-thaif' => ['images/mosque.jpg', 'images/umroh/makkah-ihram.webp'],
        'umroh-plus-turki' => ['images/istanbul.jpg', 'images/umroh/madinah-nabawi.webp'],
        'umroh-plus-dubai' => ['images/dubai.jpg', 'images/umroh/makkah-pilgrims.webp'],
        'umroh-plus-al-ula' => ['images/desert.jpg', 'images/umroh/madinah-courtyard.webp'],
    ];

    public function up(): void
    {
        foreach ($this->images as $slug => [$old, $new]) {
            DB::table('packages')->where('slug', $slug)
                ->whereIn('image_path', [$old, str_replace('.jpg', '.webp', $old)])
                ->update(['image_path' => $new]);
        }
    }

    public function down(): void
    {
        foreach ($this->images as $slug => [$old, $new]) {
            DB::table('packages')->where('slug', $slug)->where('image_path', $new)
                ->update(['image_path' => $old]);
        }
    }
};
