<?php

use App\Http\Controllers\Auth\RegisteredParentController;
use Illuminate\Support\Facades\Route;

Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::livewire('/', 'pages::ppdb.informasi')->name('informasi');

    Route::middleware('guest')->group(function (): void {
        Route::get('/register', [RegisteredParentController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredParentController::class, 'store'])->name('register.store');
    });

    Route::middleware('auth')->group(function (): void {
        Route::livewire('/daftar', 'pages::ppdb.daftar')->name('daftar');
    });
});
