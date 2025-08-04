<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Admin', 'Contributor', 'Subscriber'] as $roles) {
            Role::factory()->create([
                'role_name' => substr($roles, 0, 1),
                'formal_name' => $roles,
            ]);
        }
    }
}
