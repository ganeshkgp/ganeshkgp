<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Priya Nair',
                'role' => 'Founder, KeralaKraft — Kochi',
                'avatar' => null,
                'content' => 'Ganesh delivered our entire D2C brand identity and website in under 3 weeks. The UI was exactly what we envisioned — modern, clean, and perfect for our Indian audience. Highly recommend!',
                'rating' => 5,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Arjun Mehta',
                'role' => 'CTO, FinTrack Solutions — Pune',
                'avatar' => null,
                'content' => 'We needed a Laravel + Vue SPA built fast without cutting corners. Ganesh nailed it — clean architecture, solid API design, and delivered ahead of schedule. Will definitely work with him again.',
                'rating' => 5,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sneha Reddy',
                'role' => 'Product Manager, EduBridge — Hyderabad',
                'avatar' => null,
                'content' => 'Ganesh built our Flutter app from scratch and integrated it with our existing backend seamlessly. The attention to detail and communication throughout the project was outstanding.',
                'rating' => 5,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            DB::table('testimonials')->updateOrInsert(
                ['name' => $testimonial['name'], 'role' => $testimonial['role']],
                array_merge($testimonial, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ 3 testimonials seeded.');
    }
}
