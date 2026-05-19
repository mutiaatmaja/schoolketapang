<?php

use App\Http\Controllers\Ppdb\SpmbRegistrationDetailController;
use App\Http\Controllers\Ppdb\SpmbRegistrationRecapPdfController;
use Illuminate\Support\Facades\Route;

Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::livewire('/', 'pages::ppdb.informasi')->name('informasi');
    Route::livewire('/statistik', 'pages::ppdb.statistik')->name('statistik');
    Route::livewire('/kategori/belum-validasi', 'pages::ppdb.kategori-status')
        ->name('kategori.belum-validasi')
        ->defaults('status', 'submitted')
        ->defaults('title', 'Belum Validasi');
    Route::livewire('/kategori/terverifikasi', 'pages::ppdb.kategori-status')
        ->name('kategori.terverifikasi')
        ->defaults('status', 'verified')
        ->defaults('title', 'Terverifikasi');
    Route::livewire('/kategori/lulus', 'pages::ppdb.kategori-status')
        ->name('kategori.lulus')
        ->defaults('status', 'lulus')
        ->defaults('title', 'Lulus');
    Route::livewire('/kategori/cadangan', 'pages::ppdb.kategori-status')
        ->name('kategori.cadangan')
        ->defaults('status', 'cadangan')
        ->defaults('title', 'Cadangan');
    Route::livewire('/kategori/ditolak', 'pages::ppdb.kategori-status')
        ->name('kategori.ditolak')
        ->defaults('status', 'ditolak')
        ->defaults('title', 'Ditolak');
    Route::livewire('/daftar', 'pages::ppdb.daftar')->name('daftar');
    Route::get('/detail/{registrationNumber}', SpmbRegistrationDetailController::class)->name('detail');
    Route::get('/rekap/{registrationNumber}/pdf', SpmbRegistrationRecapPdfController::class)->name('rekap-pdf');
});
