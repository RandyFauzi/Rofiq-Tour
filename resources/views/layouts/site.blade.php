<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Rofiq Tour — Perjalanan menuju Baitullah')</title>
<meta name="description" content="@yield('description', 'Temukan program umroh dan perjalanan plus bersama Rofiq Tour. Pilih paket, lihat jadwal, dan konsultasikan rencana perjalanan Anda.')">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
@vite(['resources/css/site.css', 'resources/js/site.js'])
</head>
<body class="public-site">
<a href="#main" class="skip-link">Langsung ke konten</a>
<div class="topbar"><span>Setiap perjalanan dimulai dari niat yang baik.</span><a href="{{ route('consultation') }}">Rencanakan perjalanan Anda <x-icon name="arrow" :size="13"/></a></div>
<header class="site-header"><div class="container nav-wrap"><x-brand/>
<button class="menu-toggle" aria-label="Buka navigasi" aria-expanded="false" aria-controls="site-nav"><x-icon name="menu"/></button>
<nav id="site-nav" aria-label="Navigasi utama">
@foreach(['home'=>'Beranda','packages.index'=>'Paket Umroh','about'=>'Tentang Kami','gallery'=>'Galeri','articles.index'=>'Artikel'] as $route=>$label)
<a class="{{ request()->routeIs($route) ? 'active' : '' }}" href="{{ route($route) }}">{{ $label }}</a>
@endforeach
<a class="nav-contact" href="{{ route('contact') }}">Hubungi Kami <x-icon name="arrow" :size="16"/></a>
</nav></div></header>
<main id="main">
@if($errors->any() && !request()->routeIs('consultation'))
<div class="container"><div class="alert alert-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
@endif
@yield('content')</main>
<section class="closing-cta"><div class="container"><div><span class="eyebrow">MULAI PERJALANAN ANDA</span><h2>Niat baik Anda,<br><em>kami temani langkahnya.</em></h2></div><div><p>Belum tahu program yang paling sesuai?<br>Mari bicarakan rencana perjalanan Anda.</p><a class="btn btn-light" href="{{ route('consultation') }}">Konsultasi perjalanan <x-icon name="arrow"/></a></div></div></section>
<footer class="site-footer"><div class="container footer-grid"><div><x-brand/><p>{{ $siteSettings?->tagline }}</p><span class="footer-caption">Ibadah yang tenang.<br>Perjalanan yang bermakna.</span></div><div><h3>Jelajahi</h3><a href="{{ route('packages.index') }}">Program Umroh</a><a href="{{ route('gallery') }}">Galeri Perjalanan</a><a href="{{ route('articles.index') }}">Cerita & Panduan</a></div><div><h3>Informasi</h3><a href="{{ route('about') }}">Tentang Rofiq Tour</a><a href="{{ route('faq') }}">Pertanyaan Umum</a><a href="{{ route('privacy') }}">Kebijakan Privasi</a></div><div><h3>Terhubung</h3><a href="{{ route('contact') }}">Kontak & Kantor</a><a href="{{ route('consultation') }}">Konsultasi Gratis</a>@if($siteSettings?->contact_email)<a href="mailto:{{ $siteSettings->contact_email }}">{{ $siteSettings->contact_email }}</a>@endif @if($siteSettings?->instagram_url)<a href="{{ $siteSettings->instagram_url }}" rel="noopener" target="_blank">Instagram <x-icon name="arrow" :size="14"/></a>@endif</div></div><div class="container footer-bottom"><span>© {{ date('Y') }} {{ $siteSettings?->brand_name ?? 'Rofiq Tour' }}. Semua hak dilindungi.</span><span>Menemani perjalanan, merawat kepercayaan.</span></div></footer>
@if($siteSettings?->instagram_url)<a class="floating-instagram" href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram resmi Rofiq Tour"><x-icon name="instagram" :size="24"/></a>@endif
@if($siteSettings?->whatsapp_number)<a class="floating-contact" href="https://wa.me/{{ $siteSettings->whatsapp_number }}" target="_blank" rel="noopener" aria-label="Chat admin melalui WhatsApp"><x-icon name="whatsapp" :size="26"/> <span>Chat admin</span></a>@endif
</body></html>
