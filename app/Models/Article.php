<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'image',
        'image_color',
        'author',
        'author_bio',
        'tags',
        'view_count',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'view_count' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title before creating
        static::creating(function ($article) {
            if (empty($article->slug) && !empty($article->title)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
        });

        // Update slug if title changes
        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
        });
    }

    /**
     * Generate unique slug from title
     */
    protected static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug exists and make it unique
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to order by published date.
     */
    public function scopeLatestPublished($query)
    {
        return $query->published()->orderBy('published_at', 'desc');
    }

    /**
     * Get formatted reading time.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200); // Average reading speed: 200 words per minute
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Get the full URL for the article image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // If image already starts with http/https, return as is
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // Return storage path
        return asset('storage/' . $this->image);
    }
}
