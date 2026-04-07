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
        Schema::table('feedings', function (Blueprint $table) {
            $table->foreignId('feed_id')->nullable()->after('ration_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedings', function (Blueprint $table) {
            $table->dropForeign(['feed_id']);
            $table->dropColumn('feed_id');
        });
    }
};
