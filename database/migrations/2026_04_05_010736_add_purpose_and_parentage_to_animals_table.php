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
            $table->enum('purpose', ['breeding', 'fattening', 'milking', 'other'])->default('other')->after('species');
            $table->foreignId('sire_id')->nullable()->after('purpose')->constrained('animals')->nullOnDelete();
            $table->foreignId('dam_id')->nullable()->after('sire_id')->constrained('animals')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropForeign(['sire_id']);
            $table->dropForeign(['dam_id']);
            $table->dropColumn(['purpose', 'sire_id', 'dam_id']);
        });
    }
};
