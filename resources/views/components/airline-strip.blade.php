@php
$airlines = ['saudia'=>'Saudia', 'qatar'=>'Qatar Airways', 'turkish'=>'Turkish Airlines', 'oman'=>'Oman Air', 'emirates'=>'Emirates', 'etihad'=>'Etihad Airways', 'garuda'=>'Garuda Indonesia'];
@endphp
<section class="airline-strip" aria-label="Pilihan maskapai internasional" data-marquee>
    <div class="container airline-strip-heading"><span class="eyebrow">PILIHAN MASKAPAI INTERNASIONAL</span><button type="button" class="marquee-toggle" data-marquee-toggle aria-label="Jeda gerakan logo" aria-pressed="false"><x-icon name="pause" :size="14"/></button></div>
    <div class="airline-window">
        <div class="airline-track">
            @for($copy=0; $copy<3; $copy++)
            <ul class="airline-group" @if($copy>0) aria-hidden="true" @endif>
                @foreach($airlines as $file=>$name)<li><img src="{{ asset('images/airlines/'.$file.'.webp') }}" alt="{{ $copy===0?$name:'' }}" width="180" height="70" loading="lazy"></li>@endforeach
            </ul>
            @endfor
        </div>
    </div>
    <p>Maskapai mengikuti program keberangkatan yang Anda pilih.</p>
</section>
