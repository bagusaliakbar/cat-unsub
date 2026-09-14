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
        // Admin User
        User::factory()->create([
            'name' => 'Admin CAT',
            'email' => 'admin@cat.com',
            'password' => bcrypt('password'), // default
            'role' => 'admin',
        ]);

        // Participant User
        User::factory()->create([
            'name' => 'Peserta Ujian',
            'email' => 'peserta@cat.com',
            'password' => bcrypt('password'), // default
            'role' => 'peserta',
        ]);
    }
}
