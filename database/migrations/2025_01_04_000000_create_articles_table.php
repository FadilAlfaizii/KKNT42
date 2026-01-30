<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->string('image_color')->nullable();
            $table->string('author')->nullable();
            $table->string('author_bio')->nullable();
            $table->string('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('reading_time')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
