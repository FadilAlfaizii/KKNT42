<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    $latestArticles = \App\Models\Article::latestPublished()
        ->select('id', 'title', 'excerpt', 'category', 'image', 'author', 'published_at')
        ->limit(3)
        ->get()
        ->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'excerpt' => $article->excerpt,
                'category' => $article->category,
                'image' => $article->image_url,
                'author' => $article->author,
                'published_at' => $article->published_at,
            ];
        });

    $statistics = [
        'totalArticles' => \App\Models\Article::count(),
    ];

    return Inertia::render('Home', [
        'latestArticles' => $latestArticles,
        'statistics' => $statistics,
    ]);
})->name('Home');

Route::get('/tentang', function () {
    return Inertia::render('Tentang');
});

Route::get('/peta-interaktif', [MapController::class, 'index'])->name('PetaInteraktif');

Route::get('/statistik', function () {
    return Inertia::render('Statistik');
})->name('Statistik');

Route::get('/artikel', [ArticleController::class, 'index'])->name('Artikel');

Route::get('/artikel/{id}', [ArticleController::class, 'show'])->name('DetailArtikel');

Route::get('/dashboard', function () {
    $statistics = [
        'totalPenduduk' => 5234,
        'jumlahKK' => 1456,
        'jumlahRT' => 8,
        'jumlahRW' => 3,
        'laki' => 2678,
        'perempuan' => 2556,
        'anggaran' => 2450000000,
        'realisasi' => 1850000000,
    ];

    return Inertia::render('Dashboard', [
        'statistics' => $statistics,
    ]);
})->name('Dashboard');

// Fallback login route - redirect to Filament admin login
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');
