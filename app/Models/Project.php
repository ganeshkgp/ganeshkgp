<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'technologies',
        'live_url',
        'github_url',
        'demo_url',
        'position',
        'color',
        'featured',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'technologies' => 'array',
        'position' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    protected function technologies(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => json_encode($value)
        );
    }

    protected function position(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? ['x' => 0, 'y' => 0, 'z' => 0],
            set: fn ($value) => json_encode($value)
        );
    }

    public function getTechArrayAttribute()
    {
        return $this->technologies;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/projects/' . $this->image);
        }

        // Return a placeholder or default image
        return 'https://via.placeholder.com/400x300/0a0a0a/00ffff?text=' . urlencode($this->name);
    }
}
