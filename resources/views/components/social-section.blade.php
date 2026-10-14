@props(['settings'])
<section class="section social-section" id="sosial-media">
    <div class="container social-grid">
        <div class="social-art" data-reveal="left"><img src="{{ asset('images/features/social.webp') }}" alt="Ilustrasi dua ponsel menampilkan inspirasi perjalanan umroh Rofiq Tour" width="1254" height="1254" loading="lazy"></div>
        <div class="social-copy" data-reveal="right">
            <span class="eyebrow">SOSIAL MEDIA RESMI</span>
            <h2>Terhubung lebih<br>dekat bersama<br>Rofiq Tour.</h2>
            <p>Ikuti perjalanan, informasi, dan inspirasi spiritual terbaru langsung dari sosial media resmi kami.</p>
            <div class="social-actions">
                @if($settings?->instagram_url)<a class="btn btn-gold" href="{{ $settings->instagram_url }}" target="_blank" rel="noopener"><x-icon name="instagram" :size="18"/> Selengkapnya <x-icon name="arrow" :size="17"/></a><a class="social-handle" href="{{ $settings->instagram_url }}" target="_blank" rel="noopener">@rofiqtour di Instagram</a>@endif
            </div>
            <div class="social-info-grid">
                <div class="social-info"><strong>Perjalanan</strong><span>cerita dari Tanah Suci</span></div>
                <div class="social-info"><strong>Inspirasi</strong><span>bekal sebelum beribadah</span></div>
                <a class="social-info" href="{{ route('consultation') }}"><strong>Terhubung <x-icon name="arrow" :size="16"/></strong><span>konsultasi bersama tim</span></a>
            </div>
        </div>
    </div>
</section>
