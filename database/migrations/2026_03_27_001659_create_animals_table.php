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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->string('name_or_tag');
            $table->string('species');
            $table->string('breed')->nullable();
            $table->enum('sex', ['male', 'female', 'other']);
            $table->date('birth_date')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->enum('status', ['active', 'sold', 'dead'])->default('active');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
