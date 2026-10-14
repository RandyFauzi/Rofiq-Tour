@extends('layouts.site')
@section('title','Rencana Terkirim — Rofiq Tour')
@section('content')
<section class="section"><div class="container narrow success-card"><span class="success-icon"><x-icon name="check" :size="36"/></span><span class="eyebrow">LANGKAH PERTAMA ANDA</span><h1>Terima kasih,<br><em>{{ $inquiry->name }}.</em></h1><p>Rencana perjalanan Anda telah diterima untuk ditindaklanjuti tim kami.</p><div class="reference">Referensi konsultasi: {{ $inquiry->reference }}</div>@if($whatsappUrl)<a class="btn btn-primary" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">Lanjutkan melalui WhatsApp <x-icon name="arrow"/></a>@endif<a class="text-link" href="{{ route('packages.index') }}">Jelajahi program lain <x-icon name="arrow"/></a><p class="footnote">Konsultasi belum merupakan konfirmasi pendaftaran atau reservasi seat.</p></div></section>
@endsection
