@extends('layouts.admin')
@section('title',($record->exists?'Edit ':'Tambah ').$definition['label'])
@section('content')
<a class="text-link" href="{{ route('admin.content.index',$resource) }}">← Kembali ke {{ $definition['label'] }}</a><div class="admin-heading"><div><span class="eyebrow">FORMULIR DATA</span><h1>{{ $record->exists?'Edit':'Tambah' }} {{ $definition['label'] }}</h1><p>Isi informasi yang akan digunakan pada website.</p></div></div>
@if($resource==='inquiries')<section class="admin-panel inquiry-detail"><h2>Rencana calon jamaah</h2><dl><dt>Nama</dt><dd>{{ $record->name }}</dd><dt>WhatsApp</dt><dd>{{ $record->phone }}</dd><dt>Kota / jumlah jamaah</dt><dd>{{ $record->city??'—' }} · {{ $record->travelers }} orang</dd><dt>Program</dt><dd>{{ $record->package?->name??'Minta rekomendasi' }}</dd><dt>Keberangkatan</dt><dd>{{ $record->departure?->departure_date?->format('d/m/Y')??'Belum dipilih' }}</dd><dt>Pesan</dt><dd>{{ $record->message??'—' }}</dd><dt>Persetujuan diterima</dt><dd>{{ $record->consented_at->format('d/m/Y H:i') }}</dd><dt>Referensi</dt><dd>{{ $record->reference }}</dd></dl><a class="btn btn-outline" href="https://wa.me/{{ preg_replace('/^0/','62',preg_replace('/[^0-9]/','',$record->phone)) }}" target="_blank" rel="noopener">Hubungi calon jamaah <x-icon name="arrow" :size="17"/></a></section>@endif
<form class="admin-form admin-panel" action="{{ $record->exists?route('admin.content.update',[$resource,$record->id]):route('admin.content.store',$resource) }}" method="POST" enctype="multipart/form-data">@csrf @if($record->exists) @method('PUT') @endif
<div class="admin-fields">@foreach($definition['fields'] as $name=>$field)
@php
$type=$field['type']??'text';
$value=old($name,$record->getAttribute($name));
if ($value instanceof \DateTimeInterface) $value=$value->format($type==='date'?'Y-m-d':'Y-m-d\TH:i');
if (!$record->exists && $value===null && in_array($name,['sort_order','available_seats','capacity','duration_days','deposit_amount'])) $value=['sort_order'=>0,'available_seats'=>40,'capacity'=>40,'duration_days'=>9,'deposit_amount'=>10000000][$name];
@endphp
<div class="admin-field {{ in_array($type,['textarea','image'])?'field-wide':'' }}">
@if($type==='checkbox')<label class="checkbox-label admin-checkbox"><input name="{{ $name }}" type="checkbox" value="1" @checked($value)><span>{{ $field['label'] }}</span></label>
@else<label for="{{ $name }}">{{ $field['label'] }} @if(str_contains($field['rules']??'','required'))<span>*</span>@endif</label>
@if($type==='textarea')<textarea id="{{ $name }}" name="{{ $name }}" rows="{{ in_array($name,['body','description','about_text','itinerary'])?8:4 }}">{{ $value }}</textarea>
@elseif(isset($field['relation']) || isset($field['options']))<select id="{{ $name }}" name="{{ $name }}"><option value="">Pilih {{ strtolower($field['label']) }}</option>@foreach(($options[$name]??$field['options']) as $optionValue=>$optionLabel)<option value="{{ $optionValue }}" @selected((string)$value===(string)$optionValue)>{{ $optionLabel }}</option>@endforeach</select>
@elseif($type==='image')@if($record->image_path)<img class="admin-image-preview" src="{{ \App\Support\Media::url($record->image_path) }}" alt="Foto saat ini">@endif<input id="{{ $name }}" type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG, atau WebP. Maksimal 5 MB. Biarkan kosong untuk mempertahankan foto saat ini.</small>
@else<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" @if($type==='number') min="0" step="1" @endif>@endif
@endif
@error($name)<p class="field-error">{{ $message }}</p>@enderror
</div>@endforeach</div><div class="admin-form-footer"><button type="submit" class="btn btn-primary">Simpan perubahan <x-icon name="check" :size="17"/></button><a href="{{ route('admin.content.index',$resource) }}">Batal</a></div></form>
@endsection
