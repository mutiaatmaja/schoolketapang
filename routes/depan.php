<?php

use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('depan.beranda');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::livewire('/profil', 'pages::depan.profil')->name('depan.profil');
