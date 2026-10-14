<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::firstOrCreate(['id' => 1], [
            'brand_name' => 'Rofiq Tour',
            'whatsapp_number' => '6289620339209',
            'instagram_url' => 'https://www.instagram.com/rofiqtour/',
            'tagline' => 'Menemani setiap langkah menuju Baitullah.',
            'about_text' => 'Rofiq Tour hadir untuk menemani perjalanan ibadah Anda dengan persiapan yang terarah, informasi yang terbuka, dan pelayanan yang personal. Kami percaya perjalanan yang baik dimulai dari komunikasi yang jelas, pilihan program yang sesuai, serta pendampingan di setiap langkah.',
        ]);
        Office::firstOrCreate(['name' => 'Kantor Rofiq Tour'], [
            'city' => 'Banjarmasin',
            'address' => 'Jl. A. Yani No.404 KM.5,5 No, Pemurus Dalam, Kec. Banjarmasin Tim., Kota Banjarmasin, Kalimantan Selatan 70249',
            'phone' => '+62 896-2033-9209',
            'map_url' => 'https://maps.app.goo.gl/2QsarYz8mRQxtWzZ9',
            'is_published' => true,
        ]);
    }
}
