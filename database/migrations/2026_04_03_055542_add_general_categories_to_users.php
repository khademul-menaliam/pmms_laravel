<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\User::all()->each(function (\App\Models\User $user) {
            \App\Models\Category::updateOrCreate(
                ['user_id' => $user->id, 'name' => 'General', 'type' => 'income'],
                ['color' => '#64748b', 'is_default' => true, 'slug' => 'general-income']
            );

            \App\Models\Category::updateOrCreate(
                ['user_id' => $user->id, 'name' => 'General', 'type' => 'expense'],
                ['color' => '#64748b', 'is_default' => true, 'slug' => 'general-expense']
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without deleting data that might be in use
    }
};
