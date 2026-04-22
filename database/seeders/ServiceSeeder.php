<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Laravel & PHP Development',
                'description' => 'Scalable web apps, REST APIs, SaaS platforms, and custom admin panels built with Laravel — clean architecture, fast delivery.',
                'icon' => 'icons/services/code-bracket.svg',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'MEVN Stack Development',
                'description' => 'Full-stack JavaScript apps with MongoDB, Express, Vue.js, and Node.js — real-time dashboards, APIs, and SPAs.',
                'icon' => 'icons/services/cube.svg',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Flutter Mobile Apps',
                'description' => 'Beautiful cross-platform Android & iOS apps with a single codebase — smooth animations, native performance, fast launches.',
                'icon' => 'icons/services/device-phone-mobile.svg',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'C# & Unity Game Development',
                'description' => '2D and 3D games, AR/VR experiences, and interactive simulations built with C# and Unity for mobile, PC, and web.',
                'icon' => 'icons/services/puzzle-piece.svg',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Python Development',
                'description' => 'Automation scripts, data pipelines, ML model integration, web scraping, and backend APIs with Django or FastAPI.',
                'icon' => 'icons/services/command-line.svg',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'REST API & System Design',
                'description' => 'Well-documented RESTful APIs, microservices architecture, database optimisation, and cloud deployment on AWS or DigitalOcean.',
                'icon' => 'icons/services/circle-stack.svg',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'UI/UX Design & Prototyping',
                'description' => 'Figma wireframes, interactive prototypes, and polished UI — designed for real users, not just to look pretty.',
                'icon' => 'icons/services/computer-desktop.svg',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Maintenance & Code Review',
                'description' => 'Ongoing support, performance audits, bug fixing, refactoring, and code reviews for existing web and mobile projects.',
                'icon' => 'icons/services/wrench-screwdriver.svg',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->updateOrInsert(
                ['title' => $service['title']],
                array_merge($service, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ 8 services seeded.');
    }
}
