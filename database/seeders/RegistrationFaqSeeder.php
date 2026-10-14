<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class RegistrationFaqSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            ['Pendaftaran', 'Bagaimana mengecek legalitas dan informasi resmi Rofiq Tour?', 'Minta nomor izin penyelenggara, identitas badan usaha, dan dokumen program kepada admin. Cocokkan informasi melalui kanal resmi instansi terkait sebelum membayar. Tim kami membantu menyediakan informasi yang diperlukan.'],
            ['Pembayaran', 'Kalau saya batal, bagaimana ketentuan pengembalian dana?', 'Ketentuan pembatalan mengikuti perjanjian tertulis dan layanan yang sudah dipesan untuk keberangkatan Anda. Minta rincian potongan, batas waktu, dan proses pengembalian kepada admin sebelum mendaftar.'],
            ['Biaya', 'Ada biaya tambahan di luar harga paket?', 'Lihat bagian termasuk dan belum termasuk pada program pilihan. Mintalah rincian tertulis untuk penerbangan penghubung, dokumen, kebutuhan pribadi, dan permintaan tambahan sebelum menyetujui pendaftaran.'],
            ['Akomodasi', 'Hotelnya dekat masjid atau perlu transportasi?', 'Lokasi, jarak, dan akses hotel mengikuti kelas serta jadwal yang dipilih. Periksa nama hotel di detail keberangkatan dan minta konfirmasi akses berjalan kaki atau transportasi kepada admin.'],
            ['Pembayaran', 'Ke rekening mana pembayaran dilakukan?', 'Minta rincian rekening resmi dan instruksi pembayaran tertulis langsung dari admin Rofiq Tour. Cocokkan nama penerima dengan dokumen pendaftaran dan simpan bukti pembayaran.'],
        ];
        foreach ($questions as $index => [$category, $question, $answer]) {
            Faq::firstOrCreate(['question' => $question], [
                'category' => $category, 'answer' => $answer, 'sort_order' => $index + 6, 'is_published' => true,
            ]);
        }
    }
}
