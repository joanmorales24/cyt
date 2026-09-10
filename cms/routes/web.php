<?php

use App\Http\Controllers\Admin\BlockImageUploadController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('index'))->name('home');
Route::get('/voice-bot', fn () => view('voice_bot'))->name('voice-bot');

// Legal pages
Route::get('/politica-de-privacidad', fn () => view('legal.privacidad'))->name('legal.privacidad');
Route::get('/terminos-y-condiciones', fn () => view('legal.terminos'))->name('legal.terminos');
Route::get('/politica-de-cookies', fn () => view('legal.cookies'))->name('legal.cookies');
Route::get('/politica-de-calidad', fn () => view('legal.calidad'))->name('legal.calidad');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/categoria/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/etiqueta/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}/', [BlogController::class, 'show'])->name('show');
});

// Leads (CRM) — throttle: 20 intentos cada 15 min (dev) / 3 en prod lo maneja el limiter
Route::post('/api/leads', [LeadController::class, 'store'])
    ->middleware(['web', 'throttle:20,15'])
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

    $categories = \App\Models\Category::whereHas('posts', fn ($q) => $q->where('status', 'published'))
        ->withMax(['posts as latest_post_at' => fn ($q) => $q->where('status', 'published')], 'updated_at')
        ->get();

    $tags = \App\Models\Tag::whereHas('posts', fn ($q) => $q->where('status', 'published'))
        ->withMax(['posts as latest_post_at' => fn ($q) => $q->where('status', 'published')], 'updated_at')
        ->get();

    $homeSeo = \App\Models\PageSeo::where('page', 'home')->first();
    $latestPostAt = $posts->max('updated_at');

    $staticLastmod = [
        'home' => $homeSeo?->updated_at ?? $latestPostAt,
        'voice-bot' => \Carbon\Carbon::createFromTimestamp(filemtime(resource_path('views/voice_bot.blade.php'))),
        'blog.index' => $latestPostAt,
        'legal' => \Carbon\Carbon::createFromTimestamp(filemtime(resource_path('views/legal/privacidad.blade.php'))),
    ];

    return response()->view('sitemap', compact('posts', 'categories', 'tags', 'staticLastmod'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Catch-all: rutas inexistentes devuelven 404 real (importante para SEO/GEO,
// un soft-redirect a home confunde a los motores de búsqueda)
Route::fallback(fn () => abort(404));
