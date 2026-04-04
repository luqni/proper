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
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mother_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignId('father_id')->nullable()->constrained('animals')->nullOnDelete();
            $table->date('mating_date');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'pregnant', 'completed', 'failed'])->default('planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breedings');
    }
};
