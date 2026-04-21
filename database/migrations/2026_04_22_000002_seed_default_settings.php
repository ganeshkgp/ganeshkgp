<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'hero_greeting' => 'Namaste, I\'m',
                'hero_name' => 'Ganesh Kgp',
                'hero_tagline' => 'Full-Stack Developer · Laravel · MEVN · Flutter · Unity · Python',
                'hero_bio' => 'Based in Kharagpur, West Bengal, I build production-ready web apps, mobile apps, and games — from Laravel & MEVN backends to Flutter cross-platform apps and C# Unity games.',
                'about_title' => 'I Build What Others Only Imagine',
                'about_bio' => 'I\'m a West Bengal (Kharagpur)-based full-stack developer with 6+ years of experience building web and mobile products for Indian and global clients. From D2C brands in Mumbai to SaaS startups in Hyderabad, I bring ideas to life with clean code and thoughtful design.',
                'about_cv_url' => null,
                'about_photo' => null,
                'hero_image' => null,
                'contact_email' => 'ganeshr848@gmail.com',
                'contact_phone' => '+917501525648',
                'contact_address' => 'Kharagpur, West Bengal, India',
                'social_facebook' => null,
                'social_twitter' => null,
                'social_instagram' => null,
                'social_linkedin' => null,
                'social_github' => null,
                'site_name' => 'Ganesh Kgp.',
                'footer_description' => 'Kharagpur-based full-stack developer crafting web apps, mobile apps & games for Indian and global clients.',
                'skills' => json_encode([
                    ['name' => 'PHP & Laravel', 'level' => 92],
                    ['name' => 'MEVN Stack (Mongo · Express · Vue · Node)', 'level' => 85],
                    ['name' => 'Flutter & Dart', 'level' => 80],
                    ['name' => 'C# & Unity', 'level' => 78],
                    ['name' => 'Python', 'level' => 82],
                    ['name' => 'REST API & System Design', 'level' => 88],
                ]),
                'brands' => json_encode(['FLIPKART', 'RAZORPAY', 'ZEPTO', 'GROWW', 'CRED', 'MEESHO']),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        //
    }
};
