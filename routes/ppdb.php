<?php

use App\Http\Controllers\Ppdb\SpmbRegistrationDetailController;
use App\Http\Controllers\Ppdb\SpmbRegistrationRecapPdfController;
use Illuminate\Support\Facades\Route;

Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::livewire('/', 'pages::ppdb.informasi')->name('informasi');
    Route::livewire('/daftar', 'pages::ppdb.daftar')->name('daftar');
    Route::get('/detail/{registrationNumber}', SpmbRegistrationDetailController::class)->name('detail');
    Route::get('/rekap/{registrationNumber}/pdf', SpmbRegistrationRecapPdfController::class)->name('rekap-pdf');
});
