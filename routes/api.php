<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/home', HomeController::class)->name('api.home');
Route::post('/contact', [ContactController::class, 'store'])->name('api.contact');
Route::get('/blogs', [BlogController::class, 'index'])->name('api.blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('api.blogs.show');
