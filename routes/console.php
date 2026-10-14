<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('rofiq:admin {email} {--name=Administrator}', function () {
    if (! filter_var($this->argument('email'), FILTER_VALIDATE_EMAIL)) {
        $this->error('Email tidak valid.');

        return 1;
    }
    if (User::where('email', $this->argument('email'))->exists()) {
        $this->error('Email sudah digunakan. Tidak ada akun yang diubah.');

        return 1;
    }
    $password = $this->secret('Kata sandi (kosongkan untuk dibuat otomatis)');
    $password = $password ?: Str::password(20);
    if (strlen($password) < 12) {
        $this->error('Gunakan minimal 12 karakter.');

        return 1;
    }
    $user = new User(['name' => $this->option('name'), 'email' => $this->argument('email'), 'password' => $password]);
    $user->is_admin = true;
    $user->save();
    $this->info('Admin dibuat: '.$user->email);
    $this->line('Kata sandi: '.$password);

    return 0;
})->purpose('Buat admin secara eksplisit; tidak membuat akun bawaan dari seeder');
