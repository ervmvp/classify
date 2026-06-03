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
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'admin123'
        ]);
        User::create([
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'role' => 'teacher',
            'password' => 'teacher123'
        ]);
        User::create([
            'name' => 'Student',
            'email' => 'student@example.com',
            'role' => 'student',
            'password' => 'student123'
        ]);
    }
}
