<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Article;
use App\Models\Departure;
use App\Models\DepartureRate;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\GalleryPhoto;
use App\Models\Hotel;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SiteSettingsSeeder::class);
        $this->call(FeatureBannerSeeder::class);
        $this->call(RegistrationFaqSeeder::class);
        if (! app()->environment(['local', 'testing'])) {
            return;
        }
        DB::transaction(function () {
            $saudia = Airline::firstOrCreate(['code' => 'SV'], ['name' => 'Saudia']);
            $turkish = Airline::firstOrCreate(['code' => 'TK'], ['name' => 'Turkish Airlines']);
            $emirates = Airline::firstOrCreate(['code' => 'EK'], ['name' => 'Emirates']);
            $makkah = Hotel::firstOrCreate(['name' => 'Hotel Makkah — konfirmasi admin', 'city' => 'Makkah'], ['stars' => 4, 'distance_meters' => null]);
            $premium = Hotel::firstOrCreate(['name' => 'Hotel Premium Makkah — konfirmasi admin', 'city' => 'Makkah'], ['stars' => 5, 'distance_meters' => null]);
            $madinah = Hotel::firstOrCreate(['name' => 'Hotel Madinah — konfirmasi admin', 'city' => 'Madinah'], ['stars' => 4, 'distance_meters' => null]);
            $data = [
                ['thaif', 'Thaif', 'Arab Saudi', 'Umroh Plus Thaif', 'Sejuknya pegunungan, hangatnya perjalanan ibadah.', 'images/umroh/makkah-ihram.webp', 30900000, $saudia->id, 9],
                ['turki', 'Turki', 'Turki', 'Umroh Plus Turki', 'Dari Tanah Suci menuju jejak sejarah dua benua.', 'images/umroh/madinah-nabawi.webp', 42900000, $turkish->id, 12],
                ['dubai', 'Dubai', 'Uni Emirat Arab', 'Umroh Plus Dubai', 'Ibadah yang bermakna, pengalaman kota yang istimewa.', 'images/umroh/makkah-pilgrims.webp', 38900000, $emirates->id, 11],
                ['al-ula', 'Al-Ula', 'Arab Saudi', 'Umroh Plus Al-Ula', 'Menjelajahi keindahan alam dan warisan peradaban.', 'images/umroh/madinah-courtyard.webp', 31900000, $saudia->id, 10],
            ];
            foreach ($data as $i => [$slug,$destinationName,$country,$name,$summary,$image,$price,$airlineId,$duration]) {
                $packageImage = match ($slug) {
                    'thaif' => 'images/umroh/makkah-ihram.webp',
                    'turki' => 'images/umroh/madinah-nabawi.webp',
                    'dubai' => 'images/umroh/makkah-pilgrims.webp',
                    'al-ula' => 'images/umroh/madinah-courtyard.webp',
                };
                $destination = Destination::firstOrCreate(['slug' => $slug], ['name' => $destinationName, 'country' => $country, 'description' => $summary, 'image_path' => $packageImage]);
                $package = Package::firstOrCreate(['slug' => 'umroh-plus-'.$slug], [
                    'destination_id' => $destination->id, 'name' => $name, 'summary' => $summary,
                    'description' => 'Luangkan waktu untuk beribadah dengan tenang di Makkah dan Madinah, lalu lengkapi perjalanan dengan pengalaman di '.$destinationName.'. Program disusun agar Anda memiliki waktu untuk mempersiapkan diri, mengikuti bimbingan ibadah, dan menikmati setiap langkah perjalanan. Rincian hotel, penerbangan, serta itinerary akhir akan dikonfirmasi sebelum pendaftaran.',
                    'image_path' => $packageImage, 'is_published' => true, 'is_featured' => true, 'sort_order' => $i,
                ]);
                if (! $package->features()->exists()) {
                    foreach (['Tiket pesawat internasional pulang-pergi', 'Visa umroh', 'Akomodasi Makkah dan Madinah', 'Makan sesuai program perjalanan', 'Pendampingan ibadah dan manasik', 'City tour sesuai itinerary'] as $n => $description) {
                        $package->features()->create(['type' => 'included', 'description' => $description, 'sort_order' => $n]);
                    }
                    foreach (['Pembuatan paspor dan persyaratan kesehatan', 'Pengeluaran pribadi dan kelebihan bagasi'] as $n => $description) {
                        $package->features()->create(['type' => 'excluded', 'description' => $description, 'sort_order' => $n]);
                    }
                }
                if (! $package->itineraryDays()->exists()) {
                    foreach (['Persiapan & keberangkatan' => 'Berkumpul di bandara dan berangkat bersama rombongan.', 'Madinah & ziarah' => 'Ibadah di Masjid Nabawi dan agenda ziarah sesuai program.', 'Makkah & umroh' => 'Pelaksanaan umroh bersama pembimbing dan waktu ibadah mandiri.', 'Perjalanan plus & kepulangan' => 'Menikmati destinasi pilihan sebelum kembali ke tanah air.'] as $title => $description) {
                        $package->itineraryDays()->create(['day_number' => $package->itineraryDays()->count() + 1, 'title' => $title, 'description' => $description]);
                    }
                }
                for ($n = 0; $n < 2; $n++) {
                    $date = today()->addMonthsNoOverflow($n + 1)->startOfMonth()->addDays(5 + $i * 4);
                    $departure = Departure::firstOrCreate(['package_id' => $package->id, 'airline_id' => $airlineId, 'departure_date' => $date->format('Y-m-d'), 'duration_days' => $duration], [
                        'departure_city' => 'Jakarta', 'status' => $n === 0 ? 'confirmed' : 'tentative', 'capacity' => 40, 'available_seats' => $i === 2 && $n === 0 ? 0 : 18 + $i, 'deposit_amount' => 10000000,
                    ]);
                    foreach (['silver' => $makkah, 'platinum' => $premium] as $tier => $hotel) {
                        foreach (['quad' => 0, 'triple' => 1500000, 'double' => 3500000] as $room => $extra) {
                            DepartureRate::firstOrCreate(['departure_id' => $departure->id, 'tier' => $tier, 'room_type' => $room], [
                                'makkah_hotel_id' => $hotel->id, 'madinah_hotel_id' => $madinah->id, 'price' => $price + $extra + ($tier === 'platinum' ? 5000000 : 0),
                            ]);
                        }
                    }
                }
                GalleryPhoto::firstOrCreate(['title' => 'Inspirasi perjalanan '.$destinationName], ['destination_id' => $destination->id, 'image_path' => $packageImage, 'sort_order' => $i, 'is_published' => true]);
            }
            $faqs = [
                ['Pendaftaran', 'Bagaimana cara mendaftar perjalanan?', 'Pilih program dan jadwal yang sesuai, lalu isi formulir konsultasi. Tim kami akan membantu menjelaskan ketersediaan, rincian biaya, persyaratan, dan langkah berikutnya.'],
                ['Biaya', 'Apa saja yang termasuk dalam paket?', 'Fasilitas setiap paket ditampilkan pada halaman detail. Biaya tambahan seperti paspor, kebutuhan kesehatan, kelebihan bagasi, dan pengeluaran pribadi dijelaskan terpisah. Selalu konfirmasikan rincian akhir kepada admin.'],
                ['Dokumen', 'Dokumen apa yang perlu saya siapkan?', 'Siapkan paspor, identitas diri, serta dokumen kesehatan sesuai ketentuan keberangkatan. Untuk kebutuhan vaksin dan persyaratan terbaru, konsultasikan kepada admin sebelum membuat rencana perjalanan.'],
                ['Pembayaran', 'Berapa uang muka untuk mengunci seat?', 'Nominal DP dapat berbeda tiap keberangkatan dan ditampilkan pada jadwal. Permintaan konsultasi belum mengunci seat; admin akan menjelaskan proses pendaftaran dan pembayaran resmi.'],
                ['Keberangkatan', 'Bisa berangkat dari luar Jakarta?', 'Silakan sampaikan kota asal kepada tim kami. Ketersediaan penerbangan penghubung dan biaya tambahan akan dikonfirmasi sesuai program pilihan.'],
                ['Pendampingan', 'Apakah ada pendampingan untuk jamaah lansia?', 'Sampaikan kebutuhan pendampingan, mobilitas, dan kondisi perjalanan saat konsultasi agar tim dapat membantu menilai program yang sesuai.'],
            ];
            foreach ($faqs as $n => [$category,$question,$answer]) {
                Faq::firstOrCreate(['question' => $question], compact('category', 'answer') + ['sort_order' => $n, 'is_published' => true]);
            }
            foreach ([
                ['persiapan-sebelum-umroh', 'Langkah kecil untuk perjalanan yang lebih tenang', 'Persiapan', 'Mulai dari dokumen hingga kesiapan diri, susun persiapan umroh secara bertahap.', 'images/features/manasik.webp'],
                ['memilih-program-umroh', 'Memilih program umroh yang sesuai dengan Anda', 'Panduan', 'Kenali durasi, fasilitas, dan kebutuhan perjalanan sebelum menentukan pilihan.', 'images/umroh/madinah-nabawi.webp'],
                ['mengenal-umroh-plus', 'Ibadah dan perjalanan: mengenal umroh plus', 'Inspirasi', 'Sebuah perjalanan untuk mendekatkan diri, sekaligus melihat jejak sejarah.', 'images/umroh/makkah-pilgrims.webp'],
            ] as [$slug,$title,$category,$excerpt,$image]) {
                Article::firstOrCreate(['slug' => $slug], compact('title', 'category', 'excerpt') + ['body' => $excerpt."\n\nMulailah dengan menentukan kebutuhan perjalanan Anda: tanggal yang memungkinkan, jumlah anggota keluarga, serta kenyamanan yang dibutuhkan. Komunikasikan kebutuhan ini sejak awal agar pilihan program lebih terarah.\n\nPeriksa fasilitas yang termasuk, biaya di luar paket, pilihan hotel, dan rincian jadwal. Mintalah penjelasan tertulis sebelum mengambil keputusan.\n\nLuangkan waktu mengikuti manasik dan mempelajari rangkaian ibadah. Tim kami siap membantu menjelaskan pilihan program melalui halaman konsultasi.", 'image_path' => $image, 'published_at' => now()->subDays(3)]);
            }
        });
    }
}
