# Riset Payung Madinah untuk Rofiq Tour

Tanggal observasi: 14 Oktober 2026. Referensi: https://payungmadinah.id/.
Tujuan: memahami UI, UX, struktur konten, fitur, dan kebutuhan teknis sebelum membangun website HTML statis. Ini dokumentasi riset, belum implementasi website.

## Metode dan cakupan

- MCP Playwright: membaca DOM/aksesibilitas, CSS computed, halaman publik, tautan, form, dan mencoba interaksi tanpa mengirim pendaftaran.
- Beranda diperiksa pada viewport desktop 1440×900 dan mobile 390×844.
- Crawl 15 halaman utama di `pages.json`; pemeriksaan tambahan halaman visa, detail paket Turki, dan satu detail artikel sebagai contoh template.
- Halaman katalog, FAQ, berita, kontak, galeri, umroh gratis, dan doorprize juga dibuka di browser untuk melihat konten setelah JavaScript berjalan.
- Skill `ui-ux-pro-max` digunakan sebagai kerangka penilaian; skill `extract-design-system` digunakan untuk panduan ekstraksi. CLI ekstraksi gagal karena executable Chromium lokal belum tersedia, sehingga token diambil langsung dari CSS situs lewat MCP browser. Tidak ada hasil CLI yang diklaim berhasil.
- Tidak menguji pengiriman form, pembayaran, akun privat, setiap detail artikel/paket, maupun seluruh kombinasi perangkat. Klaim legalitas, rating, jumlah jamaah, dan harga adalah konten situs; belum diverifikasi independen.

## Karakter produk dan alur pengguna

Situs merupakan website pemasaran travel umroh dengan katalog dan konten edukasi. Tujuan konversi utamanya konsultasi WhatsApp. Dari halaman publik yang diperiksa, tidak ditemukan checkout pembayaran atau pemesanan mandiri lengkap.

Alur utama: melihat janji layanan → membaca bukti kepercayaan → memilih destinasi → melihat jadwal/hotel/harga → membaca fasilitas dan FAQ → konsultasi admin. Alur lain: membaca artikel → melihat paket; mengunjungi kantor → menghubungi admin cabang; mengikuti kampanye → mengisi data dan mengirim bukti.

## Susunan beranda

1. Header: logo, navigasi, tautan Portal TL dan Portal Jamaah. Mobile memakai hamburger dan akses sosial.
2. Hero: kolase destinasi Turki, Dubai, Al-Ula, Thaif; panel konten bertumpuk di atas bagian bawah gambar. Judul, manfaat, harga mulai, CTA WhatsApp, CTA ke program, dan indikator kepercayaan.
3. Deretan logo maskapai sebagai bukti mitra; daftar logo berulang mendukung pola marquee.
4. Keunggulan: carousel sembilan banner fasilitas dengan tombol sebelumnya/berikutnya dan indikator.
5. Sosial media: ilustrasi ponsel, tautan platform, dan statistik pengikut.
6. Program: empat kartu destinasi dengan foto, harga, durasi, manfaat, dan tombol konsultasi.
7. Jadwal: tab September–Desember 2026, filter Semua/Thaif/Turki/Dubai, status keberangkatan dan seat, hotel Silver/Platinum, serta tombol tampilkan semua.
8. Manasik: materi, tanya jawab, dan praktik persiapan ibadah.
9. Cara daftar: konsultasi → DP/kunci seat → manasik → berangkat.
10. Rincian biaya: termasuk dan belum termasuk.
11. Testimoni jamaah.
12. FAQ singkat dengan accordion.
13. Konsultasi: nama, nomor WhatsApp, program, rencana keberangkatan; penjelasan bahwa tombol membuka WhatsApp dengan pesan terisi.
14. Footer: kontak, alamat kantor, program, informasi perusahaan, kebijakan, dan sosial media.

Elemen global tambahan: tombol Instagram/WhatsApp/pilihan paket yang melayang, drawer pilihan paket, modal sosial, modal portal, dan kontrol nasyid/audio.

## Design system yang diamati

| Peran | Nilai dari CSS beranda |
|---|---|
| Latar utama | `#faf7f1` |
| Latar alternatif | `#f1ebdf` |
| Panel | `#ffffff` |
| Panel hangat | `#fbf6ec` |
| Teks utama | `#141416` |
| Teks sekunder | `#5a554d` |
| Aksen emas | `#c9a35f` |
| Hover emas | `#b8924c` |
| Teks aksen | `#7a5c33` |
| Brand gold | `#b19167` |
| Border | `rgba(20,20,22,.1)` |
| Lebar konten maksimum | `1180px` |
| Heading beranda | Plus Jakarta Sans |
| Body beranda | Poppins |
| Logo wordmark | Philosopher |
| Navigasi/elemen halaman lain | Outfit juga terdeteksi |

Nuansa: putih-krem-emas, ramah dan premium, foto destinasi dominan, panel membulat, tombol berbentuk pill, garis tipis, dan bayangan lembut. Hierarki konten menggunakan eyebrow kecil, heading tebal, paragraf muted, lalu CTA. Detail computed komponen tersimpan di `design-observed.json`; nilainya merupakan observasi beranda, bukan sistem lengkap seluruh situs.

Mobile memakai gambar hero tersendiri, konten satu kolom, judul sekitar 25,6px, header sekitar 60px, dan panel yang menumpuk di bawah gambar. Pada screenshot mobile, tiga tombol melayang di sisi kanan masuk ke area harga/CTA. Untuk Rofiq Tour sebaiknya cukup satu tombol WhatsApp dan beri ruang aman.

## Peta halaman dan fungsi

| Halaman | Isi dan fungsi yang ditemukan |
|---|---|
| `/` | Landing utama, program, filter jadwal, fasilitas, testimoni, konsultasi |
| `/umroh` | Katalog 13 paket saat observasi; filter bulan/program/maskapai; desktop grid/list; empty state |
| `/umroh/umroh-plus-turki` | Contoh detail: flyer dapat diperbesar, informasi perjalanan/hotel, benefit, syarat, deskripsi, CTA WhatsApp dan berbagi |
| `/berita` | Pencarian GET `q`, filter tag, featured artikel, kartu artikel, pagination |
| `/berita/{slug}` | Artikel panjang, heading terstruktur, metadata, artikel terkait |
| `/faq` | 47 pertanyaan, 8 kategori, pencarian, accordion, empty state, CTA bantuan |
| `/kontak` | Kantor Jakarta/Bandung/Palembang, alamat dan admin cabang |
| `/tentang-kami` | Profil perusahaan dan anggota tim beserta jabatan/keterangan |
| `/sosial-media` | Halaman promosi akun resmi Instagram, TikTok, YouTube dan platform lain |
| `/galeri` | Dokumentasi jamaah dan pilihan destinasi Dubai/Turki/Thaif; semua interaksi galeri belum diuji |
| `/karir` | Jalur fresh graduate/experienced/magang, lowongan, filter posisi, email rekrutmen dan tombol salin |
| `/umroh-gratis` | Kampanye, syarat, form bertahap, data diri, persetujuan, upload bukti/link sosial |
| `/cerita-inspiratif` | Kampanye ulasan, hadiah, form bertahap, data trip/tour leader dan upload bukti |
| `/doorprizemanasik` | Challenge konten, form identitas/program/tanggal/link IG atau TikTok, tutorial dan UI kode konfirmasi |
| `/tanyaumrohhaji` | Landing promosi tersendiri: manfaat, destinasi, fasilitas, paket dan CTA |
| `/tanyacs` | Landing promosi tersendiri dengan jadwal dan form WhatsApp |
| `/visa` | Halaman promosi layanan pengajuan visa; tidak ditemukan form pengajuan pada pemeriksaan |
| `/syarat-ketentuan` | Ketentuan pendaftaran, dokumen, DP, pembatalan dan biaya |
| `/kebijakan-privasi` | Informasi penggunaan data/cookies/analytics |

## Interaksi yang benar-benar dicoba

| Percobaan | Hasil |
|---|---|
| Beranda: tab Oktober | Aktif, ringkasan berubah menjadi 58 jadwal |
| Beranda: filter Turki setelah Oktober | Ringkasan hasil 11 keberangkatan Turki |
| Beranda: FAQ pertama | Membuka jawaban di dalam accordion |
| Portal Jamaah | Membuka informasi bahwa portal sedang disiapkan; bukan login aktif |
| Hamburger mobile | Dapat dibuka dan ditutup |
| Katalog desktop: List | Tombol berubah menjadi `is-active` |
| Katalog: September + Temukan Sekarang | Jumlah berubah dari 13 menjadi 8 paket |
| FAQ: pencarian vaksin | Menampilkan empty state pada percobaan tersebut |

Pencarian FAQ perlu ditingkatkan: kata populer seperti vaksin idealnya menemukan jawaban yang menyebut meningitis. Jangan menganggap semua label fungsi berarti seluruh alur belakangnya sudah bekerja. Form pendaftaran kampanye dan kode konfirmasi hanya diperiksa dari struktur; tidak dikirim.

## Kebutuhan statis vs layanan tambahan

HTML/CSS/JavaScript native cukup untuk menu responsive, tab, accordion, carousel sederhana, filter katalog, pencarian data lokal, grid/list, galeri/lightbox, modal, tombol berbagi, dan form yang menyusun pesan WhatsApp. Artikel dapat berupa halaman HTML individual dengan metadata SEO.

Form WhatsApp dapat memakai `encodeURIComponent()` dan `wa.me`. Gunakan pesan yang membawa nama paket, tanggal, kelas kamar, serta jumlah jamaah. Di referensi, banyak CTA paket/jadwal memakai pesan umum yang sama; konteks pilihan pengguna berpotensi hilang.

Fitur yang memerlukan endpoint atau layanan tambahan:

- Umroh gratis: form POST multipart ke `/services/umrohgratis/add`.
- Doorprize: form POST ke `/doorprizemanasik/daftar`; verifikasi dan penerbitan kode konfirmasi tidak cukup dengan HTML saja.
- Upload bukti kampanye, database peserta, notifikasi, pengelolaan admin, autentikasi portal, pembayaran, dan stok seat waktu nyata.
- Jumlah pembaca artikel akurat lintas pengguna membutuhkan pencatatan terpusat.

Status seat statis dapat ditampilkan, tetapi harus diperbarui manual dan diberi waktu pembaruan. `localStorage` bukan pengganti database pendaftaran.

## Hal yang layak diadopsi dan diperbaiki

Adopsi: alur pemasaran yang terstruktur; harga/durasi di kartu; pemisahan termasuk/belum termasuk; penjelasan cara daftar; FAQ sebelum CTA akhir; foto asli jamaah dan kantor; konsultasi yang mudah.

Perbaikan berbasis observasi:

- Katalog masih Agustus–September ketika beranda sampai Desember. Gunakan satu sumber data paket/jadwal dan default bulan yang masih relevan. Beranda masih memilih September pada tanggal observasi Oktober.
- Tautan footer Dubai, Turki, dan Al-Ula sama-sama menuju `/umroh?filter=thaif`. Setiap destinasi harus punya filter yang benar.
- Detail paket Turki memiliki durasi 9 hari, sementara beranda mempromosikan Turki 12–15 hari; beberapa bagian hotel/kelas dalam detail juga berbeda. Validasi konsistensi data per paket.
- Katalog memakai judul SEO tahun 2025; halaman sosial memakai title “Tim Kami”. Perbarui metadata sesuai halaman.
- Galeri memiliki lebih dari satu H1 pada pemeriksaan DOM. Gunakan satu H1 yang jelas untuk struktur halaman.
- Tombol melayang bertumpuk menutupi area CTA di mobile. Kurangi jumlahnya.
- Beberapa halaman menggunakan campuran font dan gaya. Pakai satu sistem tipografi yang konsisten, idealnya maksimal dua keluarga font.
- Navigasi utama sangat padat. Untuk versi awal, cukup Beranda, Paket, Tentang, Galeri, Artikel, Kontak; FAQ dan informasi lain bisa di footer.
- Audio sebaiknya opsional dengan kontrol jelas; hormati preferensi reduced motion untuk animasi.
- Klaim kepercayaan Rofiq Tour harus memakai data dan bukti milik sendiri; jangan menyalin nomor izin, ulasan, statistik, identitas, atau foto jamaah referensi sebagai milik Rofiq Tour.

## Arsitektur yang disarankan untuk proyek berikutnya

```text
Rofiq Tour/
├── index.html
├── paket.html
├── tentang.html
├── galeri.html
├── artikel.html
├── faq.html
├── kontak.html
├── paket/
│   └── umroh-plus-thaif.html
├── artikel/
│   └── panduan-persiapan-umroh.html
├── assets/
│   ├── css/
│   │   ├── tokens.css
│   │   ├── base.css
│   │   ├── components.css
│   │   └── pages.css
│   ├── js/
│   │   ├── main.js
│   │   ├── catalog.js
│   │   ├── gallery.js
│   │   └── consultation.js
│   ├── data/
│   │   ├── packages.json
│   │   ├── articles.json
│   │   └── faq.json
│   ├── images/
│   ├── icons/
│   └── fonts/
├── robots.txt
├── sitemap.xml
└── docs/
    └── reference-payungmadinah/
```

Prinsip: HTML semantik memuat konten inti; CSS membagi token/reset/komponen/penyesuaian halaman; JS hanya untuk interaksi. Muat script halaman hanya di halaman yang memerlukannya. Utamakan konten yang masih terbaca saat JavaScript gagal.

Data paket minimal: id, slug, nama, destinasi, tanggal keberangkatan, durasi, maskapai, hotel tiap kelas, harga tiap kelas/kamar, status, waktu pembaruan, foto/flyer, fasilitas, itinerary, dan CTA. Simpan harga sebagai angka, format dengan `Intl.NumberFormat('id-ID')`.

HTML detail paket dan artikel sebaiknya benar-benar hadir pada file, bukan seluruh isi baru dimunculkan melalui fetch. JSON digunakan untuk katalog/filter. Jika data membesar, generator kecil saat pengembangan dapat membuat HTML dari satu sumber; hasil deployment tetap HTML statis tanpa framework. Ini opsi kemudian, belum diperlukan untuk mulai.

Untuk versi tanpa build, header/footer bisa disalin dengan blok yang konsisten; jangan menjadikan navigasi penting bergantung pada fetch partial. Jalankan melalui HTTP Laragon/static server karena fetch JSON melalui `file://` tidak andal.

## Prioritas versi pertama

Versi awal: beranda, katalog paket, beberapa detail paket, tentang, kontak, galeri sederhana, FAQ, dan konsultasi WhatsApp. Tambahkan artikel jika kontennya tersedia. Kampanye upload, portal, dan pembayaran merupakan tahap terpisah bila kebutuhan operasionalnya sudah jelas.

Kriteria penerimaan: nyaman di mobile, CTA tidak tertutup, keyboard/focus berfungsi, label form jelas, filter punya empty state, gambar WebP/AVIF dengan ukuran tetap dan lazy loading di bawah fold, satu H1, metadata unik, tautan benar, serta data paket konsisten.

## Berkas bukti

- `pages.json`: ringkasan hasil crawl halaman utama (teks dipotong untuk dokumentasi, bukan arsip lengkap situs).
- `design-observed.json`: token CSS beranda dan computed style komponen.
- `home-desktop.png` dan `home-mobile.png`: screenshot viewport pada saat observasi.

Kesimpulan teknis: pola pemasaran dan sebagian besar interaksi publik situs referensi cocok diterapkan sebagai website statis yang sederhana. Pilihan arsitektur untuk Rofiq Tour adalah HTML multipage + CSS terstruktur + JavaScript modular kecil + data katalog terpisah.
