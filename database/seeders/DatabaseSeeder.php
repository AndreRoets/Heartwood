<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a regular user
        User::factory()->create([
            'username' => 'testuser',
            'name' => 'Test User'
        ]);

        // Create an admin user
        User::factory()->create([
            'username' => 'admin',
            'name' => 'Admin User',
            'is_admin' => true,
        ]);

        // Create another admin user
        User::factory()->create([
            'username' => 'newadmin',
            'name' => 'New Admin User',
            'is_admin' => true,
        ]);

        // Create a new admin user as requested
        User::factory()->create([
            'username' => 'admin',
            'name' => 'Admin',
            'is_admin' => true,
        ]);
    }
}
