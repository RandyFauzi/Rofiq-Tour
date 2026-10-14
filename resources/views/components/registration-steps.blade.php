@php
$steps = [
 ['phone', 'Konsultasi', 'Ceritakan tanggal, kota asal, dan jumlah jamaah. Kami bantu mencocokkan program dengan kebutuhan Anda.', 'Siapkan rencana tanggal, jumlah peserta, dan kebutuhan khusus keluarga.', 'Konsultasikan rencana'],
 ['shield', 'Konfirmasi pendaftaran', 'Periksa jadwal, hotel, rincian biaya, dan ketentuan pembayaran sebelum menyetujui pendaftaran.', 'Seat dikonfirmasi admin setelah proses pendaftaran dan pembayaran resmi. Formulir konsultasi belum mengunci kursi.', 'Tanyakan proses pendaftaran'],
 ['calendar', 'Dokumen & manasik', 'Lengkapi persyaratan perjalanan dan ikuti bimbingan untuk mempersiapkan rangkaian ibadah.', 'Tim membantu memeriksa kelengkapan dokumen. Jadwal manasik dan persyaratan disampaikan sesuai keberangkatan.', 'Tanyakan persiapan umroh'],
 ['star', 'Berangkat dengan tenang', 'Terima informasi titik kumpul, bagasi, dan kontak pendamping sebelum memulai perjalanan.', 'Simpan kontak tim dan dokumen perjalanan. Konfirmasikan kebutuhan mobilitas serta pendampingan sejak awal.', 'Lihat pilihan perjalanan'],
];
@endphp
<section class="section journey-section" id="cara-daftar" data-journey>
 <div class="container">
  <div class="journey-heading" data-reveal><span class="eyebrow">CARA DAFTAR</span><h2>Empat langkah,<br>semuanya ditemani.</h2><p>Pilih langkah untuk mengenal proses perjalanan Anda.</p></div>
  <div class="journey-track" style="--journey-progress: 0">
   @foreach($steps as [$icon,$title,$text,$detail,$action])
   <button type="button" class="journey-step {{ $loop->first ? 'is-active' : '' }}" data-step="{{ $loop->index }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" aria-controls="journey-detail" data-detail="{{ $detail }}" data-action="{{ $action }}" data-url="{{ $loop->last ? route('packages.index') : route('consultation') }}">
    <span class="journey-marker"><x-icon :name="$icon" :size="24"/></span>
    <span class="journey-card"><span class="eyebrow">LANGKAH {{ $loop->iteration }}</span><strong>{{ $title }}</strong><span>{{ $text }}</span></span>
   </button>
   @endforeach
  </div>
  <div class="journey-detail" id="journey-detail" data-reveal><a class="btn journey-action" href="{{ route('consultation') }}">{{ $steps[0][4] }} <x-icon name="arrow"/></a><p data-journey-description aria-live="polite">{{ $steps[0][3] }}</p></div>
 </div>
</section>
