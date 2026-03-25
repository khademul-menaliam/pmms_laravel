<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 30)->default('manual');
            $table->string('channel', 30)->default('dashboard');
            $table->date('reminder_date');
            $table->string('status', 20)->default('pending');
            $table->text('notes')->nullable();
            $table->string('related_resource')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'reminder_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
