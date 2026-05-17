<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('index'))->name('home');
Route::get('/voice-bot', fn () => view('voice_bot'))->name('voice-bot');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/categoria/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/etiqueta/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}/', [BlogController::class, 'show'])->name('show');
});

// Sitemap
Route::get('/sitemap.xml', function () {
    $posts = \App\Models\Post::where('status', 'published')->orderByDesc('published_at')->get();
    return response()->view('sitemap', compact('posts'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
