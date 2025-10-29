<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MediaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mail-test', function () {
    Mail::raw('This is a test email from Laravel', function ($message) {
        $message->to('ganeshr848@gmail.com')
            ->subject('Laravel SMTP test');
    });
    return 'Mail queued/sent (check recipient)';
});

// Public API Routes
Route::prefix('v1')->group(function () {
    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/featured', [ProjectController::class, 'featured']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);

    // Skills
    Route::get('/skills', [SkillController::class, 'index']);

    // Contact
    Route::post('/contact', [ContactController::class, 'store']);
});

// Protected API Routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Projects Management
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    // Skills Management
    Route::post('/skills', [SkillController::class, 'store']);
    Route::put('/skills/{skill}', [SkillController::class, 'update']);
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy']);

    // Contact Messages Management
    Route::get('/contact/messages', [ContactController::class, 'index']);
    Route::get('/contact/messages/{message}', [ContactController::class, 'show']);
    Route::put('/contact/messages/{message}/read', [ContactController::class, 'markAsRead']);
    Route::put('/contact/messages/{message}/reply', [ContactController::class, 'markAsReplied']);
    Route::put('/contact/messages/{message}/archive', [ContactController::class, 'archive']);
    Route::delete('/contact/messages/{message}', [ContactController::class, 'destroy']);

    // Media Management
    Route::get('/media', [MediaController::class, 'index']);
    Route::post('/media', [MediaController::class, 'store']);
    Route::get('/media/{id}', [MediaController::class, 'show']);
    Route::delete('/media/{filename}', [MediaController::class, 'destroy']);
});
