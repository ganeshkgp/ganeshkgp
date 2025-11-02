<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ScraperController;

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

    // Home API for frontend data
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/home/projects', [HomeController::class, 'projects']);
    Route::get('/home/services', [HomeController::class, 'services']);
    Route::get('/home/planets', [HomeController::class, 'planets']);
    Route::get('/home/blogs', [HomeController::class, 'blogs']);
    Route::get('/home/blogs/{slug}', [HomeController::class, 'blogDetails']);
    Route::post('/home/contact', [HomeController::class, 'storeContact']);
    Route::post('/home/blogs/generate', [HomeController::class, 'generateBlogPost']);

    // Blog Comments and Likes
    Route::post('/blogs/{slug}/like', [HomeController::class, 'likeBlog']);
    Route::post('/blogs/{slug}/comments', [HomeController::class, 'storeComment']);
    Route::get('/blogs/{slug}/comments', [HomeController::class, 'getComments']);
    Route::post('/comments/{comment}/like', [HomeController::class, 'likeComment']);
    Route::post('/comments/{comment}/reply', [HomeController::class, 'replyToComment']);

    // Contact
    Route::post('/contact', [ContactController::class, 'store']);

    // Scraper (Public read-only)
    Route::get('/scraper/feeds', [ScraperController::class, 'getAvailableFeeds']);
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

  // Scraper Management (Protected)
    Route::post('/scraper/article', [ScraperController::class, 'scrapeArticle']);
    Route::post('/scraper/multiple', [ScraperController::class, 'scrapeMultiple']);
});


Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Cache cleared';
});

Route::get('test-email', function() {
    Mail::raw('This is a test email from Laravel', function ($message) {
        $message->to('ganeshr848@gmail.com')
                ->subject('Laravel SMTP test');
    });
    return 'Test email sent (check recipient)';
});
