<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'category',
        'reading_time',
        'is_published',
        'is_featured',
        'published_at',
        'sort_order',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Scope to get only published blogs
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope to get featured blogs
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by sort order and published date
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Generate reading time based on content length
     */
    public function generateReadingTime(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    /**
     * Get the comments for the blog.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the approved comments for the blog.
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->approved();
    }

    /**
     * Get the likes for the blog.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(BlogLike::class);
    }

    /**
     * Check if the blog is liked by the current user or IP.
     */
    public function isLikedBy($user = null, $ipAddress = null): bool
    {
        if ($user) {
            return $this->likes()->where('user_id', $user->id)->exists();
        }

        if ($ipAddress) {
            return $this->likes()->where('ip_address', $ipAddress)->exists();
        }

        return false;
    }

    /**
     * Add a fake number to likes count.
     */
    public function getLikesWithFakeCountAttribute(): int
    {
        return $this->likes_count + rand(50, 500);
    }

    /**
     * Add a fake number to comments count.
     */
    public function getCommentsWithFakeCountAttribute(): int
    {
        return $this->comments_count + rand(20, 200);
    }

    /**
     * Save the model and generate reading time automatically
     */
    public function save(array $options = [])
    {
        if ($this->isDirty('content')) {
            $this->reading_time = $this->generateReadingTime();
        }
        return parent::save($options);
    }
}
