@props(['departure'])
@php
$fareRows = collect(['silver','platinum'])->map(function ($tier) use ($departure) {
    $rates=$departure->rates->where('tier',$tier);
    return $rates->firstWhere('room_type','quad') ?? $rates->first();
})->filter();
$logo=match($departure->airline->code) {'SV'=>'saudia','TK'=>'turkish','EK'=>'emirates','QR'=>'qatar','EY'=>'etihad','GA'=>'garuda','WY'=>'oman',default=>null};
@endphp
<article class="schedule-card" data-schedule-card data-month="{{ $departure->departure_date->format('Y-m') }}" data-destination="{{ $departure->package->destination->slug }}">
    <div class="schedule-date">
        <div><strong>{{ $departure->departure_date->format('d') }}</strong><span>{{ strtoupper($departure->departure_date->translatedFormat('M')) }}</span></div>
        <p>{{ $departure->duration_days }} hari perjalanan</p>
        <div class="schedule-badges"><span class="schedule-badge {{ $departure->status==='confirmed'?'badge-confirmed':'badge-tentative' }}">{{ $departure->status==='confirmed'?'Confirmed date':'To be confirmed' }}</span><span class="schedule-badge {{ $departure->available_seats===0?'badge-full':'badge-available' }}">{{ $departure->available_seats===0?'Full seat':$departure->available_seats.' seat tersedia' }}</span></div>
    </div>
    <div class="schedule-program"><h3>{{ $departure->package->name }}</h3>@if($logo)<img src="{{ asset('images/airlines/'.$logo.'.webp') }}" alt="{{ $departure->airline->name }}" width="110" height="40" loading="lazy">@else<span>{{ $departure->airline->name }}</span>@endif<small>Dari {{ $departure->departure_city }}</small></div>
    <div class="schedule-fares">@foreach($fareRows as $rate)<div class="schedule-fare"><span class="tier-badge tier-{{ $rate->tier }}">{{ strtoupper($rate->tier) }}</span><div class="fare-hotels"><strong>{{ $rate->makkahHotel->name }}</strong><span> · </span><strong>{{ $rate->madinahHotel->name }}</strong></div><div class="fare-price"><strong>{{ \App\Support\Money::compact($rate->price) }}</strong><small>PER JAMAAH · {{ strtoupper($rate->room_type) }}</small></div></div>@endforeach</div>
    <div class="schedule-action"><a class="btn btn-schedule" href="{{ route('consultation',['package'=>$departure->package_id,'departure'=>$departure->id]) }}"><x-icon :name="$departure->available_seats===0?'clock':'phone'" :size="17"/> {{ $departure->available_seats===0?'Daftar tunggu':'Tanya seat' }}</a><a class="schedule-detail-link" href="{{ route('packages.show',$departure->package) }}#jadwal">Rincian program</a></div>
</article>
