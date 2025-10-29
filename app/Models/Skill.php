<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'experience_level',
        'icon',
        'color',
        'position',
        'proficiency',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'position' => 'array',
        'proficiency' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    protected function position(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? ['x' => 0, 'y' => 0, 'z' => 0],
            set: fn ($value) => json_encode($value)
        );
    }

    public function getProficiencyPercentageAttribute()
    {
        return round($this->proficiency * 100);
    }
}
