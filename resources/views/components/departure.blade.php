@props(['departure'])
<article class="departure-card">
<div class="date-box"><strong>{{ $departure->departure_date->format('d') }}</strong><span>{{ $departure->departure_date->translatedFormat('M Y') }}</span></div>
<div class="departure-info"><span class="eyebrow">{{ $departure->package->destination->name }} · {{ $departure->duration_days }} HARI</span><h3>{{ $departure->package->name }}</h3><div class="departure-meta"><span>{{ $departure->airline->name }}</span><span>Dari {{ $departure->departure_city }}</span><span class="status {{ $departure->status==='confirmed'?'confirmed':'' }}">{{ $departure->status==='confirmed'?'Terkonfirmasi':'Menunggu konfirmasi' }}</span></div></div>
<div class="departure-price"><small>Mulai dari / jamaah</small><strong>{{ \App\Support\Money::rupiah($departure->rates->min('price')) }}</strong><span>{{ $departure->available_seats>0 ? $departure->available_seats.' seat tersedia' : 'Seat penuh · daftar tunggu' }}</span></div>
<a class="btn btn-outline" href="{{ route('consultation',['package'=>$departure->package_id,'departure'=>$departure->id]) }}">{{ $departure->available_seats>0?'Tanya jadwal':'Daftar tunggu' }} <x-icon name="arrow" :size="16"/></a>
</article>
