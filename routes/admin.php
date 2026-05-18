<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'role:superadmin|admin'])->name('admin.')->group(function () {
    Route::livewire('/', 'pages::admin.dashboard')->name('dashboard');
    Route::livewire('/akademik', 'pages::admin.akademik.index')->name('akademik.index');
    Route::livewire('/akademik/siswa', 'pages::admin.akademik.siswa')->name('akademik.siswa');
    Route::livewire('/akademik/guru', 'pages::admin.akademik.guru')->name('akademik.guru');
    Route::livewire('/akademik/kelas', 'pages::admin.akademik.kelas')->name('akademik.kelas');
    Route::livewire('/publik', 'pages::admin.publik.index')->name('publik.index');
    Route::livewire('/publik/info-sekolah', 'pages::admin.publik.info-sekolah')->name('publik.info-sekolah');
    Route::livewire('/publik/visi-misi', 'pages::admin.publik.visi-misi')->name('publik.visi-misi');
    Route::livewire('/publik/berita', 'pages::admin.publik.berita')->name('publik.berita');
    Route::livewire('/publik/prestasi', 'pages::admin.publik.prestasi')->name('publik.prestasi');
    Route::livewire('/ppdb', 'pages::admin.ppdb.index')->name('ppdb.index');
    Route::livewire('/ppdb/pendaftar', 'pages::admin.ppdb.pendaftar')->name('ppdb.pendaftar');
    Route::livewire('/ppdb/pendaftar/{registration:registration_number}', 'pages::admin.ppdb.detail-pendaftar')->name('ppdb.pendaftar.detail');
    Route::livewire('/ppdb/belum-validasi', 'pages::admin.ppdb.belum-validasi')->name('ppdb.belum-validasi');
    Route::livewire('/ppdb/lulus', 'pages::admin.ppdb.lulus')->name('ppdb.lulus');
    Route::livewire('/ppdb/cadangan', 'pages::admin.ppdb.cadangan')->name('ppdb.cadangan');
    Route::livewire('/ppdb/ditolak', 'pages::admin.ppdb.ditolak')->name('ppdb.ditolak');
    Route::livewire('/kelola-user', 'pages::admin.kelola-user.users')->name('users.index');
    Route::livewire('/kelola-user/role', 'pages::admin.kelola-user.roles')->name('users.roles');
});
