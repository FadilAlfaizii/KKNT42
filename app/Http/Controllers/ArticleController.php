<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index()
    {
        // Get 5 latest articles for featured section
        $latestArticles = Article::latestPublished()
            ->select('id', 'title', 'excerpt', 'category', 'image', 'author', 'published_at')
            ->limit(5)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'excerpt' => $article->excerpt,
                    'category' => $article->category,
                    'image_url' => $article->image_url,
                    'author' => $article->author,
                    'published_at' => $article->published_at,
                ];
            });

        // Get all articles for main listing
        $articles = Article::latestPublished()
            ->select('id', 'title', 'excerpt', 'category', 'image', 'author', 'published_at', 'view_count')
            ->paginate(12);

        return Inertia::render('Artikel', [
            'latestArticles' => $latestArticles,
            'articles' => $articles->items(),
            'pagination' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(int $id)
    {
        $article = Article::findOrFail($id);
        
        // Increment view count
        $article->incrementViewCount();

        // Get related articles (same category, excluding current)
        $relatedArticles = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get(['id', 'title', 'image', 'category', 'published_at']);

        return Inertia::render('DetailArtikel', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }

    /**
     * Get articles by category.
     */
    public function byCategory(string $category)
    {
        $articles = Article::published()
            ->where('category', $category)
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('Artikel', [
            'articles' => $articles->items(),
            'selectedCategory' => $category,
            'pagination' => [
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
        ]);
    }
}
