<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'price' => 39999.00,
                'period' => 'project',
                'is_featured' => false,
                'sort_order' => 1,
                'is_active' => true,
                'features' => json_encode([
                    'Single web app (Laravel or MEVN)',
                    'Up to 10 pages / screens',
                    'REST API integration',
                    'MySQL or MongoDB database',
                    '30 days post-delivery support',
                    'Source code handover',
                ]),
            ],
            [
                'name' => 'Professional',
                'price' => 89999.00,
                'period' => 'project',
                'is_featured' => true,
                'sort_order' => 2,
                'is_active' => true,
                'features' => json_encode([
                    'Full-stack web + Flutter mobile app',
                    'Laravel or MEVN backend',
                    'Android & iOS (single codebase)',
                    'Admin panel included',
                    'CI/CD & cloud deployment',
                    '60 days priority support',
                ]),
            ],
            [
                'name' => 'Enterprise',
                'price' => 199999.00,
                'period' => 'project',
                'is_featured' => false,
                'sort_order' => 3,
                'is_active' => true,
                'features' => json_encode([
                    'Custom solution — any tech stack',
                    'Laravel · MEVN · Flutter · Unity · Python',
                    'Scalable microservices architecture',
                    'Dedicated communication channel',
                    '6 months maintenance included',
                    'NDA available on request',
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('pricing_plans')->updateOrInsert(
                ['name' => $plan['name']],
                array_merge($plan, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✅ 3 pricing plans seeded.');
    }
}
