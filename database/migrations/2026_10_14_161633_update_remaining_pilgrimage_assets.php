<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $images = [
        'images/mosque.webp' => 'images/umroh/makkah-ihram.webp',
        'images/istanbul.webp' => 'images/umroh/madinah-nabawi.webp',
        'images/dubai.webp' => 'images/umroh/makkah-pilgrims.webp',
        'images/desert.webp' => 'images/umroh/madinah-courtyard.webp',
    ];

    public function up(): void
    {
        foreach (['articles', 'destinations', 'gallery_photos'] as $table) {
            foreach ($this->images as $old => $new) {
                DB::table($table)->whereIn('image_path', [$old, str_replace('.webp', '.jpg', $old)])
                    ->update(['image_path' => $new]);
            }
        }
        DB::table('articles')->where('slug', 'persiapan-sebelum-umroh')
            ->where('image_path', 'images/umroh/makkah-ihram.webp')->update(['image_path' => 'images/features/manasik.webp']);
        DB::table('articles')->where('slug', 'mengenal-umroh-plus')
            ->where('image_path', 'images/umroh/madinah-courtyard.webp')->update(['image_path' => 'images/umroh/makkah-pilgrims.webp']);
    }

    public function down(): void
    {
        // Pilgrimage illustrations remain valid content when the migration is rolled back.
    }
};
