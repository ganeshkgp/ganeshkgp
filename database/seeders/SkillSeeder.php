<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            [
                'name' => 'PHP/Laravel',
                'experience_level' => 'Backend Expert',
                'icon' => '🐘',
                'color' => '#777BB4',
                'position' => ['x' => -3, 'y' => 2, 'z' => 0],
                'proficiency' => 0.95,
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Vue.js',
                'experience_level' => 'Frontend Specialist',
                'icon' => '💚',
                'color' => '#4FC08D',
                'position' => ['x' => 3, 'y' => 2, 'z' => 0],
                'proficiency' => 0.90,
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Python',
                'experience_level' => 'Multi-purpose Developer',
                'icon' => '🐍',
                'color' => '#3776AB',
                'position' => ['x' => 0, 'y' => 3, 'z' => -2],
                'proficiency' => 0.85,
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Flutter',
                'experience_level' => 'Cross-platform Expert',
                'icon' => '📱',
                'color' => '#02569B',
                'position' => ['x' => -3, 'y' => -1, 'z' => 1],
                'proficiency' => 0.80,
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Unity',
                'experience_level' => 'Game Developer',
                'icon' => '🎮',
                'color' => '#000000',
                'position' => ['x' => 3, 'y' => -1, 'z' => 1],
                'proficiency' => 0.75,
                'sort_order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'Three.js',
                'experience_level' => '3D Graphics Specialist',
                'icon' => '🎨',
                'color' => '#000000',
                'position' => ['x' => 0, 'y' => -2, 'z' => 2],
                'proficiency' => 0.88,
                'sort_order' => 6,
                'is_active' => true
            ],
            [
                'name' => 'MySQL/PostgreSQL',
                'experience_level' => 'Database Expert',
                'icon' => '🗄️',
                'color' => '#4479A1',
                'position' => ['x' => -5, 'y' => 0, 'z' => -2],
                'proficiency' => 0.92,
                'sort_order' => 7,
                'is_active' => true
            ],
            [
                'name' => 'AWS/Cloud',
                'experience_level' => 'Cloud Architect',
                'icon' => '☁️',
                'color' => '#FF9900',
                'position' => ['x' => 5, 'y' => 0, 'z' => -2],
                'proficiency' => 0.83,
                'sort_order' => 8,
                'is_active' => true
            ],
            [
                'name' => 'Docker/DevOps',
                'experience_level' => 'DevOps Engineer',
                'icon' => '🐳',
                'color' => '#2496ED',
                'position' => ['x' => 0, 'y' => 4, 'z' => 0],
                'proficiency' => 0.78,
                'sort_order' => 9,
                'is_active' => true
            ],
            [
                'name' => 'React/JavaScript',
                'experience_level' => 'Frontend Developer',
                'icon' => '⚛️',
                'color' => '#61DAFB',
                'position' => ['x' => 0, 'y' => -3, 'z' => 0],
                'proficiency' => 0.87,
                'sort_order' => 10,
                'is_active' => true
            ]
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
