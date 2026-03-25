<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending');
            $table->string('received_by', 50)->nullable();
            $table->string('received_from')->nullable();
            $table->date('expected_date')->nullable();
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_cycle', 20)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'expected_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
