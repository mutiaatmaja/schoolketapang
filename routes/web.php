<?php

use Illuminate\Support\Facades\Route;

Route::get('desain', function () {
    return view('welcome');
});

require __DIR__ . '/depan.php';
require __DIR__ . '/ppdb.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
