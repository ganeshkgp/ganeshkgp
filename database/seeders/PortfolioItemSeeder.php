<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortfolioItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Portfolio Website',
                'description' => 'Personal developer portfolio built with Laravel 13, Vue 3, Filament admin, and Tailwind CSS. Features a blog, contact form, and dynamic content management.',
                'image' => null,
                'category' => 'Web',
                'url' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'E-Commerce SaaS Platform',
                'description' => 'Multi-vendor e-commerce platform built with Laravel, Vue.js, and Razorpay integration. Supports product listings, order management, and GST invoicing.',
                'image' => null,
                'category' => 'Laravel',
                'url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Expense Tracker App',
                'description' => 'Cross-platform Flutter app for tracking daily expenses with category breakdown, monthly reports, and UPI payment detection. Available on Android & iOS.',
                'image' => null,
                'category' => 'Flutter',
                'url' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Real-Time Dashboard',
                'description' => 'MEVN stack operations dashboard with live data via Socket.IO, interactive charts, role-based access, and export to PDF/Excel.',
                'image' => null,
                'category' => 'MEVN',
                'url' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => '2D Puzzle Game',
                'description' => 'Mobile puzzle game built with Unity and C#, featuring 50+ levels, leaderboard integration, and AdMob monetisation. Published on Google Play.',
                'image' => null,
                'category' => 'Unity',
                'url' => null,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'FastAPI Microservice',
                'description' => 'Python FastAPI microservice for ML model inference with async endpoints, JWT auth, Redis caching, and Docker deployment on AWS ECS.',
                'image' => null,
                'category' => 'Python',
                'url' => null,
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            DB::table('portfolio_items')->updateOrInsert(
                ['title' => $item['title']],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ 6 portfolio items seeded.');
    }
}
