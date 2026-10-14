@props(['banners'])
@if($banners->isNotEmpty())
<section class="section feature-section" id="keunggulan">
    <div class="container">
        <div class="center-heading feature-heading">
            <span class="eyebrow">KEUNGGULAN ROFIQ TOUR</span>
            <h2>Umroh yang nyaman, diiringi<br>fasilitas lengkap dan profesional.</h2>
            <p>Geser untuk mengenal fasilitas dan layanan yang menemani perjalanan Anda.</p>
        </div>
        <div class="feature-carousel" data-carousel role="region" aria-roledescription="carousel" aria-label="Keunggulan Rofiq Tour" tabindex="0">
            <ul class="feature-track">
                @foreach($banners as $banner)
                <li class="feature-slide" data-slide role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} dari {{ $banners->count() }}: {{ $banner->title }}">
                    <img src="{{ \App\Support\Media::url($banner->image_path) }}" alt="Ilustrasi {{ $banner->title }}" width="1400" height="930" loading="lazy">
                    <div class="feature-copy"><span class="eyebrow">{{ $banner->eyebrow }}</span><h3>{{ $banner->title }}</h3><p>{{ $banner->description }}</p><span class="feature-brand">ROFIQ TOUR <span>PERJALANAN BERMAKNA</span></span></div>
                </li>
                @endforeach
            </ul>
            <div class="carousel-controls">
                <button class="carousel-arrow carousel-prev" type="button" data-carousel-prev aria-label="Banner sebelumnya"><x-icon name="chevron"/></button>
                <button class="carousel-arrow carousel-next" type="button" data-carousel-next aria-label="Banner berikutnya"><x-icon name="chevron"/></button>
                <button class="carousel-play" type="button" data-carousel-play aria-label="Jeda pergantian otomatis" aria-pressed="false"><x-icon name="pause" :size="16"/></button>
            </div>
            <div class="carousel-dots" role="group" aria-label="Pilih banner">
                @foreach($banners as $banner)<button type="button" data-carousel-dot="{{ $loop->index }}" aria-label="Tampilkan banner {{ $loop->iteration }}: {{ $banner->title }}" aria-current="{{ $loop->first?'true':'false' }}"></button>@endforeach
            </div>
            <p class="sr-only" data-carousel-status role="status" aria-live="polite"></p>
        </div>
        <p class="feature-footnote">Gambar merupakan ilustrasi. Detail fasilitas mengikuti program yang Anda pilih.</p>
    </div>
</section>
@endif
