<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'super.admin@super.com'],
            [
                'name' => 'Super Admin',
                'password' => '12345678', // This will be hashed by the model cast or attribute
                'currency' => 'USD',
                'is_superadmin' => true,
            ]
        );
    }
}
