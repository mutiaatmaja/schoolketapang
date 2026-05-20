<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('depan.beranda');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
Route::livewire('/profil', 'pages::depan.profil')->name('depan.profil');
