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
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // Energi, Protein, Mineral
            $table->decimal('protein', 8, 2)->default(0); // PK
            $table->decimal('fat', 8, 2)->default(0); // Lemak
            $table->decimal('fiber', 8, 2)->default(0); // SK
            $table->decimal('tdn', 8, 2)->default(0);
            $table->decimal('dry_matter', 8, 2)->default(0); // BK
            $table->decimal('price_per_kg', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
    }
};
