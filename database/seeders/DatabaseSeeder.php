<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SkillSeeder::class,
            ProjectSeeder::class,
            ServiceSeeder::class,
        ]);

        // Create an admin user for testing
        User::factory()->create([
            'name' => 'Ganesh K P',
            'email' => 'ganeshr848@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
