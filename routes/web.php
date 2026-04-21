<?php

use Illuminate\Support\Facades\Route;

// Serve the SPA for all non-API, non-admin routes
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api|admin).*$')->name('home');
