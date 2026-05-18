<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('desain', function () {
    return app(WelcomeController::class)();
});
Route::get('desain/admin', function () {
    return view('layouts.admin.app');
});

require __DIR__.'/depan.php';
require __DIR__.'/ppdb.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
