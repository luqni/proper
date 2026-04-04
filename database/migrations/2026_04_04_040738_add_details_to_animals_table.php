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
        Schema::table('animals', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->after('farm_id');
            $table->date('entry_date')->nullable()->after('birth_date');
            $table->decimal('initial_weight', 8, 2)->nullable()->after('weight');
            $table->text('condition_notes')->nullable()->after('photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn(['registration_number', 'entry_date', 'initial_weight', 'condition_notes']);
        });
    }
};
