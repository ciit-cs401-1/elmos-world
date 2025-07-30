<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        User::factory()->create([
            'name' => 'Marie Doe',
            'email' => 'contributor@test.com',
            'password' => 'password123',
        ]);

        User::factory(100)->create();
    }
}
