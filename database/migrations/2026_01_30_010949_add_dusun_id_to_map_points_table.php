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
        Schema::table('map_points', function (Blueprint $table) {
            $table->foreignId('dusun_id')->nullable()->after('id')->constrained('dusuns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('map_points', function (Blueprint $table) {
            $table->dropForeign(['dusun_id']);
            $table->dropColumn('dusun_id');
        });
    }
};
