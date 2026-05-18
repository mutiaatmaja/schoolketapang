<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('depan.beranda');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::livewire('/profil', 'pages::depan.profil')->name('depan.profil');
