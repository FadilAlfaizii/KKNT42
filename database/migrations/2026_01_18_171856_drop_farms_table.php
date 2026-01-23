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
        Schema::dropIfExists('farms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users',"id","farms_users_id")->index();
            $table->string('name');
            $table->text('location');
            $table->integer('capacity');
            $table->text('description');
            $table->text('image_url');
            $table->boolean('is_active');
            $table->decimal('longitude', 11, 8);
            $table->decimal('latitude', 10, 8);
            $table->decimal('pan_width');
            $table->decimal('pan_height');
            $table->decimal('pan_length');
            $table->timestamp('createdAt')->index()->useCurrent();
        });
    }
};
