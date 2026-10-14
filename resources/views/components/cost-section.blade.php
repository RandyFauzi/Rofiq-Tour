@props(['packages'])
@if($packages->isNotEmpty())
<section class="section cost-section" id="rincian-biaya" data-costs>
 <div class="container">
  <div class="reference-heading" data-reveal><div><span class="eyebrow">BIAYA & FASILITAS</span><h2>Rincian jelas.<br>Ibadah lebih tenang.</h2></div><p>Kenali fasilitas yang termasuk dan biaya di luar paket. Pilih program untuk melihat rinciannya sebelum mendaftar.</p></div>
  <div class="cost-selector" data-reveal><label for="cost-package">Program perjalanan</label><select id="cost-package" data-cost-select>@foreach($packages as $package)<option value="{{ $package->id }}">{{ $package->name }}</option>@endforeach</select><span data-cost-count aria-live="polite"></span></div>
  @foreach($packages as $package)
  <div class="cost-panel" data-cost-panel="{{ $package->id }}">
   <h3 class="cost-program-title">{{ $package->name }}</h3>
   <div class="cost-grid">
    @forelse($package->features->where('type','included') as $feature)
    <details class="cost-card" data-reveal><summary><span class="cost-icon"><x-icon name="check"/></span><strong>{{ $feature->description }}</strong><span class="cost-plus" aria-hidden="true">+</span></summary><div class="cost-card-answer">Termasuk dalam program ini. Spesifikasi, jadwal layanan, dan ketentuan akhir dijelaskan pada rincian tertulis sebelum pendaftaran.</div></details>
    @empty<p>Rincian fasilitas sedang disiapkan. Hubungi admin untuk penjelasan program.</p>@endforelse
   </div>
   <div class="cost-exclusions" data-reveal><div><span class="cost-exclusion-icon"><x-icon name="close"/></span><h3>Belum termasuk</h3><p>Konfirmasikan nominal dan kebutuhan Anda sebelum mendaftar.</p></div><ul>@forelse($package->features->where('type','excluded') as $feature)<li>{{ $feature->description }}</li>@empty<li>Hubungi admin untuk rincian biaya di luar paket.</li>@endforelse</ul></div>
   <div class="cost-footer"><p>Harga mengikuti jadwal, kelas hotel, dan pilihan kamar.</p><a class="text-link" href="{{ route('packages.show',$package) }}">Lihat rincian program <x-icon name="arrow"/></a></div>
  </div>
  @endforeach
 </div>
</section>
@endif
