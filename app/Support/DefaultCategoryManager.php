<?php

namespace App\Support;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class DefaultCategoryManager
{
    public static function defaults(): array
    {
        return [
            ['name' => 'Salary', 'type' => 'income', 'color' => '#0f766e'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#2563eb'],
            ['name' => 'Business', 'type' => 'income', 'color' => '#7c3aed'],
            ['name' => 'Investment', 'type' => 'income', 'color' => '#059669'],
            ['name' => 'Gift', 'type' => 'income', 'color' => '#d946ef'],
            ['name' => 'Food', 'type' => 'expense', 'color' => '#f97316'],
            ['name' => 'Transport', 'type' => 'expense', 'color' => '#0ea5e9'],
            ['name' => 'Rent', 'type' => 'expense', 'color' => '#ef4444'],
            ['name' => 'Utility', 'type' => 'expense', 'color' => '#22c55e'],
            ['name' => 'Shopping', 'type' => 'expense', 'color' => '#8b5cf6'],
            ['name' => 'Internet', 'type' => 'expense', 'color' => '#ec4899'],
        ];
    }

    public static function ensureFor(User $user): void
    {
        collect(self::defaults())->each(function (array $category) use ($user): void {
            Category::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'slug' => Str::slug($category['name']),
                    'type' => $category['type'],
                ],
                $category + [
                    'description' => null,
                    'is_default' => true,
                ]
            );
        });
    }
}
