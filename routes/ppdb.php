<?php

use Illuminate\Support\Facades\Route;

Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::livewire('/', 'pages::ppdb.informasi')->name('informasi');
    Route::livewire('/daftar', 'pages::ppdb.daftar')->name('daftar');
});
