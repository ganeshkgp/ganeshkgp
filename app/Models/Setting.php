<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        // Hero
        'hero_greeting',
        'hero_name',
        'hero_tagline',
        'hero_bio',
        'hero_image',
        // About
        'about_title',
        'about_bio',
        'about_cv_url',
        'about_photo',
        'skills',
        // Contact
        'contact_email',
        'contact_phone',
        'contact_address',
        // Social
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_github',
        // Site identity
        'site_name',
        'site_tagline',
        'site_favicon',
        'site_logo',
        'footer_description',
        'brands',
        // SEO meta
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_og_image',
        'meta_og_type',
        // Analytics
        'google_analytics_id',
        'google_tag_manager_id',
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
                // Hero
                'hero_greeting' => 'Namaste, I\'m',
                'hero_name'     => 'Ganesh',
                'hero_tagline'  => 'Full-Stack Developer · Laravel · MEVN · Flutter · Unity · Python',
                'hero_bio'      => 'Based in Kharagpur, West Bengal, I build production-ready web apps, mobile apps, and games — from Laravel & MEVN backends to Flutter cross-platform apps and C# Unity games.',

                // About
                'about_title' => 'I Build What Others Only Imagine',
                'about_bio'   => 'I\'m a Kharagpur-based full-stack developer with hands-on experience building web and mobile products for Indian and global clients. From D2C brands in Mumbai to SaaS startups in Hyderabad, I bring ideas to life with clean code and thoughtful design.',

                // Contact
                'contact_email'   => 'ganeshr848@gmail.com',
                'contact_phone'   => '',
                'contact_address' => 'Kharagpur, West Bengal, India',

                // Site identity
                'site_name'          => 'Ganesh KGP',
                'site_tagline'       => 'Full-Stack Developer for Hire',
                'footer_description' => 'Kharagpur-based full-stack developer crafting web apps, mobile apps & games for Indian and global clients.',

                // SEO meta
                'meta_title'       => 'Ganesh KGP — Full-Stack Developer · Laravel · Flutter · Unity',
                'meta_description' => 'Hire Ganesh, a Kharagpur-based full-stack developer specialising in Laravel, Vue.js, Flutter mobile apps, Unity games, and Python. Delivering clean, production-ready code for Indian and global clients.',
                'meta_keywords'    => 'full stack developer india, laravel developer, flutter developer, vue developer, unity game developer, python developer, freelance developer kharagpur, ganeshkgp',
                'meta_og_type'     => 'website',

                // Skills
                'skills' => [
                    ['name' => 'PHP & Laravel',                        'level' => 92],
                    ['name' => 'MEVN Stack (Mongo · Express · Vue · Node)', 'level' => 85],
                    ['name' => 'Flutter & Dart',                       'level' => 80],
                    ['name' => 'C# & Unity',                           'level' => 78],
                    ['name' => 'Python',                               'level' => 82],
                    ['name' => 'REST API & System Design',             'level' => 88],
                ],

                // Brands
                'brands' => ['FLIPKART', 'RAZORPAY', 'ZEPTO', 'GROWW', 'CRED', 'MEESHO'],
            ]
        );
    }

    /**
     * Resolved public URL for a stored file field (favicon, logo, OG image, etc.).
     */
    public function storageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::url($path);
    }

    /**
     * The effective <title> tag value.
     */
    public function getPageTitle(): string
    {
        return $this->meta_title ?: $this->site_name;
    }
}
