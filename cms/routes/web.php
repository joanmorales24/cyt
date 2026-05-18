<?php

use App\Http\Controllers\Admin\BlockImageUploadController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('index'))->name('home');
Route::get('/voice-bot', fn () => view('voice_bot'))->name('voice-bot');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/categoria/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/etiqueta/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}/', [BlogController::class, 'show'])->name('show');
});

// Leads (CRM) — throttle: 20 intentos cada 15 min (dev) / 3 en prod lo maneja el limiter
Route::post('/api/leads', [LeadController::class, 'store'])
    ->middleware('throttle:20,15')
    ->name('leads.store');

// Block image upload (admin only)
Route::post('/admin/upload-block-image', [BlockImageUploadController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('admin.upload-block-image');

// Media library (admin only)
Route::middleware(['web', 'auth'])->prefix('admin/media')->name('admin.media.')->group(function () {
    Route::get('/',          [MediaController::class, 'index'])->name('index');
    Route::post('/',         [MediaController::class, 'store'])->name('store');
    Route::patch('/{media}', [MediaController::class, 'update'])->name('update');
});

// Sitemap
Route::get('/sitemap.xml', function () {
    $posts = \App\Models\Post::where('status', 'published')->orderByDesc('published_at')->get();
    return response()->view('sitemap', compact('posts'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
