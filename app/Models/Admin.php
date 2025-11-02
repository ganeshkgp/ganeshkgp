<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_active',
        'permissions',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Check if the admin is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if admin has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Get the blogs created by this admin.
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'admin_id');
    }

    /**
     * Get the contact messages assigned to this admin.
     */
    public function contactMessages(): HasMany
    {
        return $this->hasMany(Contact::class, 'admin_id');
    }

    /**
     * Send email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }

    /**
     * Send password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }

    /**
     * Get the avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Generate avatar from name
        $name = $this->name;
        $initials = collect(explode(' ', $name))
            ->map(fn($word) => mb_substr($word, 0, 1))
            ->take(2)
            ->implode('');

        return "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&color=FFFFFF&background=00ffff&length=2&bold=true";
    }

    /**
     * Scope to get only active admins.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get admins with specific permission.
     */
    public function scopeWithPermission($query, string $permission)
    {
        return $query->whereJsonContains('permissions', $permission);
    }
}
