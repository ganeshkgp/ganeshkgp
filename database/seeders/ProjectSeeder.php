<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'E-Commerce Platform',
                'description' => 'Full-stack e-commerce solution with real-time inventory management, payment processing, and admin dashboard. Built with Laravel and Vue.js for optimal performance.',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'Stripe API'],
                'live_url' => 'https://example-ecommerce.com',
                'github_url' => 'https://github.com/ganeshkgp/ecommerce-platform',
                'position' => ['x' => 0, 'y' => 1, 'z' => -5],
                'color' => '#00ffff',
                'featured' => true,
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Mobile Banking App',
                'description' => 'Cross-platform mobile banking application with biometric authentication, real-time transactions, and budget tracking features.',
                'technologies' => ['Flutter', 'Node.js', 'PostgreSQL', 'JWT'],
                'live_url' => 'https://apps.apple.com/banking-app',
                'github_url' => 'https://github.com/ganeshkgp/mobile-banking',
                'position' => ['x' => 5, 'y' => 1, 'z' => 0],
                'color' => '#ff00ff',
                'featured' => true,
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => '3D Game Engine',
                'description' => 'Custom Unity game engine with advanced physics simulation, multiplayer support, and cross-platform compatibility.',
                'technologies' => ['Unity', 'C#', 'WebSocket', 'Photon'],
                'live_url' => 'https://steam.com/game-engine',
                'github_url' => 'https://github.com/ganeshkgp/3d-game-engine',
                'position' => ['x' => -5, 'y' => 1, 'z' => 0],
                'color' => '#ffff00',
                'featured' => false,
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'AI Analytics Dashboard',
                'description' => 'Real-time analytics dashboard with machine learning predictions, data visualization, and automated reporting.',
                'technologies' => ['Python', 'Vue.js', 'TensorFlow', 'D3.js', 'FastAPI'],
                'live_url' => 'https://analytics.example.com',
                'github_url' => 'https://github.com/ganeshkgp/ai-dashboard',
                'position' => ['x' => 0, 'y' => 1, 'z' => 5],
                'color' => '#00ff00',
                'featured' => true,
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Social Media Platform',
                'description' => 'Scalable social networking platform with real-time messaging, content sharing, and advanced privacy controls.',
                'technologies' => ['Laravel', 'Vue.js', 'Socket.io', 'Elasticsearch', 'AWS S3'],
                'live_url' => 'https://social.example.com',
                'github_url' => 'https://github.com/ganeshkgp/social-platform',
                'position' => ['x' => 7, 'y' => 1, 'z' => 7],
                'color' => '#ff6b6b',
                'featured' => false,
                'sort_order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'IoT Control System',
                'description' => 'Smart home automation system with voice control, mobile app, and integration with popular smart devices.',
                'technologies' => ['Flutter', 'Python', 'MQTT', 'Raspberry Pi', 'AWS IoT'],
                'live_url' => 'https://iot.example.com',
                'github_url' => 'https://github.com/ganeshkgp/iot-control',
                'position' => ['x' => -7, 'y' => 1, 'z' => 7],
                'color' => '#4ecdc4',
                'featured' => false,
                'sort_order' => 6,
                'is_active' => true
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
