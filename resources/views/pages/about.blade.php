@extends('layouts.site')
@section('title','Tentang Kami — Rofiq Tour')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">KENAL LEBIH DEKAT</span><h1>Perjalanan yang baik,<br><em>berawal dari kepercayaan.</em></h1><p>{{ $siteSettings?->tagline }}</p></div></section>
<section class="section"><div class="container why-grid"><div class="why-photo"><img src="{{ asset('images/umroh/madinah-courtyard.webp') }}" alt="Arsitektur masjid sebagai inspirasi perjalanan ibadah" width="800" height="960" loading="lazy"></div><div><span class="eyebrow">TENTANG ROFIQ TOUR</span><h2>Menemani langkah.<br><em>Merawat niat baik.</em></h2><div class="prose">@foreach(preg_split('/\\r?\\n\\s*\\r?\\n/',$siteSettings?->about_text??'') as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>@if($siteSettings?->license_number)<div class="notice"><x-icon name="shield"/> Nomor izin: {{ $siteSettings->license_number }}</div>@endif<a class="btn btn-primary" href="{{ route('contact') }}">Mari terhubung <x-icon name="arrow"/></a></div></div></section>
@endsection
