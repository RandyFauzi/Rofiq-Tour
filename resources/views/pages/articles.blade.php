@extends('layouts.site')
@section('title','Cerita & Panduan — Rofiq Tour')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">CERITA & PANDUAN</span><h1>Bekal untuk hati.<br><em>Inspirasi untuk perjalanan.</em></h1><p>Persiapan, panduan, dan cerita untuk menemani langkah Anda.</p></div></section>
<section class="section pt-small"><div class="container"><form class="filter-bar compact-filter" method="GET"><div><label for="q">Cari artikel</label><input name="q" id="q" value="{{ request('q') }}" placeholder="Persiapan, perjalanan, umroh..."></div><div><label for="category">Kategori</label><select id="category" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option @selected(request('category')===$category)>{{ $category }}</option>@endforeach</select></div><button class="btn btn-primary">Cari artikel <x-icon name="arrow" :size="17"/></button></form><div class="article-grid">@forelse($articles as $article)<x-article-card :article="$article"/>@empty<div class="empty-state">Belum ada artikel yang sesuai pencarian Anda.</div>@endforelse</div>{{ $articles->links() }}</div></section>
@endsection
