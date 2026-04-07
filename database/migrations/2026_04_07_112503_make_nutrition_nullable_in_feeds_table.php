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
        Schema::table('feeds', function (Blueprint $table) {
            $table->decimal('protein', 8, 2)->nullable()->change();
            $table->decimal('fat', 8, 2)->nullable()->change();
            $table->decimal('fiber', 8, 2)->nullable()->change();
            $table->decimal('tdn', 8, 2)->nullable()->change();
            $table->decimal('dry_matter', 8, 2)->nullable()->change();
            $table->decimal('price_per_kg', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
            $table->decimal('protein', 8, 2)->default(0)->change();
            $table->decimal('fat', 8, 2)->default(0)->change();
            $table->decimal('fiber', 8, 2)->default(0)->change();
            $table->decimal('tdn', 8, 2)->default(0)->change();
            $table->decimal('dry_matter', 8, 2)->default(0)->change();
            $table->decimal('price_per_kg', 12, 2)->default(0)->change();
        });
    }
};
