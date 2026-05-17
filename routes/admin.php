<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::livewire('/', 'pages::admin.dashboard')->name('dashboard');
    Route::livewire('/ppdb/pendaftar', 'pages::admin.ppdb.pendaftar')->name('ppdb.pendaftar');
});
