<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/paket', [SiteController::class, 'packages'])->name('packages.index');
Route::get('/paket/{package}', [SiteController::class, 'package'])->name('packages.show');
Route::get('/artikel', [SiteController::class, 'articles'])->name('articles.index');
Route::get('/artikel/{article}', [SiteController::class, 'article'])->name('articles.show');
Route::get('/faq', [SiteController::class, 'faq'])->name('faq');
Route::get('/galeri', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/tentang', [SiteController::class, 'about'])->name('about');
Route::get('/kontak', [SiteController::class, 'contact'])->name('contact');
Route::get('/kebijakan-privasi', [SiteController::class, 'privacy'])->name('privacy');
Route::get('/konsultasi', [SiteController::class, 'consultation'])->name('consultation');
Route::post('/konsultasi', [InquiryController::class, 'store'])->middleware('throttle:5,1')->name('consultation.store');
Route::get('/konsultasi/terkirim', [InquiryController::class, 'success'])->name('consultation.success');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'auth.session', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/akun', [AccountController::class, 'update'])->name('account.update');
    Route::get('/{resource}', [ContentController::class, 'index'])->name('content.index');
    Route::get('/{resource}/create', [ContentController::class, 'create'])->name('content.create');
    Route::post('/{resource}', [ContentController::class, 'store'])->name('content.store');
    Route::get('/{resource}/{record}/edit', [ContentController::class, 'edit'])->whereNumber('record')->name('content.edit');
    Route::put('/{resource}/{record}', [ContentController::class, 'update'])->whereNumber('record')->name('content.update');
    Route::delete('/{resource}/{record}', [ContentController::class, 'destroy'])->whereNumber('record')->name('content.destroy');
});
