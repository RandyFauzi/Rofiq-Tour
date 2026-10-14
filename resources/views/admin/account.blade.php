@extends('layouts.admin')
@section('title','Keamanan akun')
@section('content')
<div class="admin-heading"><div><span class="eyebrow">AKUN ANDA</span><h1>Keamanan akun</h1><p>{{ auth()->user()->email }}</p></div></div>
<form method="POST" action="{{ route('admin.account.update') }}" class="admin-panel admin-form">@csrf @method('PUT')
<div class="admin-fields"><div class="admin-field field-wide"><label for="current_password">Kata sandi saat ini</label><input id="current_password" name="current_password" type="password" autocomplete="current-password" required></div><div class="admin-field"><label for="password">Kata sandi baru</label><input id="password" name="password" type="password" autocomplete="new-password" minlength="12" required><small>Minimal 12 karakter, dengan huruf besar, huruf kecil, dan angka.</small></div><div class="admin-field"><label for="password_confirmation">Ulangi kata sandi baru</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required></div></div>
<div class="admin-form-footer"><button class="btn btn-primary" type="submit">Simpan kata sandi <x-icon name="check" :size="17"/></button></div></form>
@endsection
