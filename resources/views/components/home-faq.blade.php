@props(['faqs'])
<section class="section registration-faq" id="pertanyaan">
 <div class="container">
  <div class="reference-heading" data-reveal><div><span class="eyebrow">PERTANYAAN YANG SERING MASUK</span><h2>Sebelum Anda<br>mendaftar.</h2></div><p>Ini pertanyaan yang paling sering masuk ke admin. Kalau pertanyaan Anda tidak ada di sini, tanyakan langsung lewat <a href="{{ route('consultation') }}">WhatsApp.</a></p></div>
  <div class="reference-faq-grid accordion" data-faq-group>
   @foreach($faqs as $faq)<details data-reveal><summary>{{ $faq->question }}<span aria-hidden="true">+</span></summary><div class="faq-answer"><p>{{ $faq->answer }}</p></div></details>@endforeach
  </div>
  <a class="text-link faq-more" href="{{ route('faq') }}">Lihat pusat informasi <x-icon name="arrow"/></a>
 </div>
</section>
