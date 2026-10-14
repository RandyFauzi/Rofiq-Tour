@props(['departures'])
@php
$months=$departures->groupBy(fn($departure)=>$departure->departure_date->format('Y-m'));
$destinations=$departures->pluck('package.destination')->unique('slug');
@endphp
<section class="section schedule-section schedule-reference" id="jadwal">
<div class="container">
    <div class="section-heading"><div><span class="eyebrow">JADWAL KEBERANGKATAN</span><h2>Pilih tanggalnya,<br><em>kami temani perjalanan Anda.</em></h2><p>Pilih waktu dan program yang sesuai. Harga per jamaah mengikuti kelas hotel dan tipe kamar.</p></div><div class="schedule-deposit"><x-icon name="shield"/><span>Rincian DP tersedia di setiap program.<small>Konfirmasi ketersediaan bersama admin.</small></span></div></div>
    @if($departures->isNotEmpty())
    <div data-schedule data-page-size="6">
        <div class="schedule-filter-panel" data-schedule-controls hidden>
            <div class="schedule-months" role="tablist" aria-label="Bulan keberangkatan">
                @foreach($months as $month=>$items)<button type="button" role="tab" id="month-{{ $month }}" data-schedule-month="{{ $month }}" data-label="{{ $items->first()->departure_date->translatedFormat('F Y') }}" aria-selected="{{ $loop->first?'true':'false' }}" aria-controls="schedule-results" tabindex="{{ $loop->first?'0':'-1' }}"><strong>{{ $items->first()->departure_date->translatedFormat('F Y') }}</strong><span>{{ $items->count() }} jadwal</span></button>@endforeach
            </div>
            <div class="schedule-destinations" role="group" aria-label="Filter program"><button type="button" data-schedule-destination="all" aria-pressed="true">Semua</button>@foreach($destinations as $destination)<button type="button" data-schedule-destination="{{ $destination->slug }}" aria-pressed="false">{{ $destination->name }}</button>@endforeach</div>
            <div class="schedule-legend"><span><i class="legend-dot dot-confirmed"></i> Confirmed date</span><span><i class="legend-dot dot-tentative"></i> To be confirmed</span><span><i class="legend-dot dot-available"></i> Seat tersedia</span><span><i class="legend-dot dot-full"></i> Full seat, daftar tunggu</span><span><i class="legend-dot dot-tier"></i> Silver / Platinum = kelas layanan hotel</span><strong data-schedule-count role="status" aria-live="polite"></strong></div>
        </div>
        <div id="schedule-results" class="schedule-results" role="tabpanel" aria-label="Jadwal keberangkatan">
            @foreach($departures as $departure)<x-departure-card :departure="$departure"/>@endforeach
            <div class="empty-state" data-schedule-empty hidden><h3>Belum ada jadwal yang sesuai.</h3><p>Coba program atau bulan lain, atau konsultasikan rencana Anda.</p><a href="{{ route('consultation') }}" class="btn btn-primary">Konsultasi program <x-icon name="arrow"/></a></div>
        </div>
        <button class="btn schedule-more" data-schedule-more type="button" hidden>Tampilkan semua keberangkatan</button>
    </div>
    @else<div class="empty-state">Jadwal berikutnya sedang disiapkan. Konsultasikan rencana Anda kepada kami.</div>@endif
    <p class="footnote">Harga dan jadwal perlu dikonfirmasi kembali. Ketersediaan seat diperbarui oleh admin; pengajuan konsultasi belum merupakan reservasi.</p>
</div></section>
