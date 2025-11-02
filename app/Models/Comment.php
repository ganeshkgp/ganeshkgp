<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'content',
        'is_approved',
        'likes_count',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the blog that owns the comment.
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies for the comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('is_approved', true)
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the comment likes.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Check if the comment is liked by the current user or IP.
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
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get only root comments (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get formatted created_at date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y \a\t g:i A');
    }
}