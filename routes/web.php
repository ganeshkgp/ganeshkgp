<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Vue.js SPA Routes - Handle Vue Router History Mode
// These routes serve the Vue app for all SPA routes
Route::get('/projects', function () {
    return view('welcome');
});

Route::get('/blogs', function () {
    return view('welcome');
});

Route::get('/blog/{slug}', function () {
    return view('welcome');
});

Route::get('/contact', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('welcome');
});

// Vue.js SPA catch-all route for Vue Router history mode
Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '^(?!api|admin|storage|public).*');
