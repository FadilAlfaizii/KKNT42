<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\StatistikController;
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

Route::get('/statistik', [StatistikController::class, 'index'])->name('Statistik');

Route::get('/artikel', [ArticleController::class, 'index'])->name('Artikel');

Route::get('/artikel/{id}', [ArticleController::class, 'show'])->name('DetailArtikel');

Route::get('/dashboard', function () {
    // Get real-time statistics from database
    $totalPenduduk = \App\Models\Penduduk::count();
    $jumlahKK = \App\Models\Keluarga::count();
    $laki = \App\Models\Penduduk::where('jenis_kelamin', 'LAKI-LAKI')->count();
    $perempuan = \App\Models\Penduduk::where('jenis_kelamin', 'PEREMPUAN')->count();
    
    // Get RT/RW counts from keluarga
    $jumlahRT = \App\Models\Keluarga::distinct('rt')->count('rt');
    $jumlahRW = \App\Models\Keluarga::distinct('rw')->count('rw');
    
    $statistics = [
        'totalPenduduk' => $totalPenduduk,
        'jumlahKK' => $jumlahKK,
        'jumlahRT' => $jumlahRT,
        'jumlahRW' => $jumlahRW,
        'laki' => $laki,
        'perempuan' => $perempuan,
        'anggaran' => 2450000000,  // Static - from government budget
        'realisasi' => 1850000000,  // Static - manual input by admin
    ];

    return Inertia::render('Dashboard', [
        'statistics' => $statistics,
    ]);
})->name('Dashboard');

// Fallback login route - redirect to Filament admin login
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');
