<?php

namespace Database\Seeders;

use App\Models\FeatureBanner;
use Illuminate\Database\Seeder;

class FeatureBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            ['Perjalanan mulia, dimulai dari niat.', 'UMROH BERSAMA ROFIQ TOUR', 'Rencanakan perjalanan ke Makkah dan Madinah bersama tim yang membantu Anda mempersiapkan setiap langkah.', 'images/umroh/makkah-ihram.webp'],
            ['Persiapan manasik sebelum berangkat.', 'BEKAL MENUJU BAITULLAH', 'Kenali rangkaian ibadah, tanyakan hal yang belum dipahami, dan persiapkan diri sebelum memulai perjalanan umroh.', 'images/features/manasik.webp'],
            ['Akomodasi untuk perjalanan yang nyaman.', 'PILIHAN HOTEL', 'Kenali pilihan hotel Makkah dan Madinah sesuai kelas layanan. Rincian akomodasi dikonfirmasi sebelum pendaftaran.', 'images/features/hotel.webp'],
            ['Tenang beribadah di Tanah Suci.', 'MAKKAH & MADINAH', 'Luangkan waktu untuk mendekatkan diri, menikmati suasana ibadah, dan membawa pulang pengalaman yang bermakna.', 'images/umroh/madinah-nabawi.webp'],
            ['Kami temani setiap langkah Anda.', 'PENDAMPINGAN PERSONAL', 'Diskusikan kebutuhan keluarga, persiapan dokumen, serta rencana perjalanan Anda bersama tim Rofiq Tour.', 'images/umroh/makkah-pilgrims.webp'],
        ];
        foreach ($banners as $order => [$title, $eyebrow, $description, $image]) {
            FeatureBanner::firstOrCreate(['title' => $title], [
                'eyebrow' => $eyebrow, 'description' => $description, 'image_path' => $image,
                'sort_order' => $order, 'is_published' => true,
            ]);
        }
    }
}
