@extends('layouts.site')
@section('title',$article->title.' — Rofiq Tour')
@section('description',$article->excerpt)
@section('content')
<article class="section article-detail"><div class="container narrow"><a class="text-link" href="{{ route('articles.index') }}">← Semua cerita</a><div class="article-meta">{{ $article->category }} · {{ $article->published_at->translatedFormat('d F Y') }}</div><h1>{{ $article->title }}</h1><p class="lead">{{ $article->excerpt }}</p><img class="detail-cover" src="{{ \App\Support\Media::url($article->image_path) }}" alt="{{ $article->title }}" width="1000" height="650"><div class="prose">@foreach(preg_split('/\\r?\\n\\s*\\r?\\n/',$article->body) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div></div></article>
@endsection
