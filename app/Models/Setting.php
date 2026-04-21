<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'hero_greeting',
        'hero_name',
        'hero_tagline',
        'hero_bio',
        'hero_image',
        'about_title',
        'about_bio',
        'about_cv_url',
        'about_photo',
        'skills',
        'contact_email',
        'contact_phone',
        'contact_address',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_github',
        'site_name',
        'footer_description',
        'brands',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'brands' => 'array',
        ];
    }

    /**
     * Always return the single settings record, creating it with defaults if missing.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'hero_greeting' => 'Namaste, I\'m',
                'hero_name' => 'Arjun Sharma',
                'hero_tagline' => 'Full-Stack Developer · Laravel · MEVN · Flutter · Unity · Python',
                'hero_bio' => 'Based in Kharagpur, West Bengal, I build production-ready web apps, mobile apps, and games — from Laravel & MEVN backends to Flutter cross-platform apps and C# Unity games.',
                'about_title' => 'I Build What Others Only Imagine',
                'about_bio' => 'I\'m a West Bengal (Kharagpur)-based full-stack developer with 6+ years of experience building web and mobile products for Indian and global clients. From D2C brands in Mumbai to SaaS startups in Hyderabad, I bring ideas to life with clean code and thoughtful design.',
                'contact_email' => 'arjun@portfo.in',
                'contact_phone' => '+91 98765 43210',
                'contact_address' => 'Kharagpur, West Bengal, India',
                'site_name' => 'Portfo.',
                'footer_description' => 'Kharagpur-based full-stack developer crafting web apps, mobile apps & games for Indian and global clients.',
                'skills' => [
                    ['name' => 'PHP & Laravel', 'level' => 92],
                    ['name' => 'MEVN Stack (Mongo · Express · Vue · Node)', 'level' => 85],
                    ['name' => 'Flutter & Dart', 'level' => 80],
                    ['name' => 'C# & Unity', 'level' => 78],
                    ['name' => 'Python', 'level' => 82],
                    ['name' => 'REST API & System Design', 'level' => 88],
                ],
                'brands' => ['FLIPKART', 'RAZORPAY', 'ZEPTO', 'GROWW', 'CRED', 'MEESHO'],
            ]
        );
    }
}
