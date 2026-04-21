<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/home', HomeController::class)->name('api.home');
Route::post('/contact', [ContactController::class, 'store'])->name('api.contact');
