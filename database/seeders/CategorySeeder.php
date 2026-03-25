<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\DefaultCategoryManager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->each(function (User $user): void {
            DefaultCategoryManager::ensureFor($user);
        });
    }
}
