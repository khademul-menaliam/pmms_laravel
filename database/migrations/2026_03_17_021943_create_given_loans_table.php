<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('given_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('person_name');
            $table->decimal('amount', 12, 2);
            $table->date('given_date');
            $table->date('expected_return_date')->nullable();
            $table->string('status', 20)->default('pending');
            $table->decimal('returned_amount', 12, 2)->default(0);
            $table->date('returned_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'expected_return_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('given_loans');
    }
};
