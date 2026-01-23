<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'author_bio')) {
                $table->string('author_bio')->nullable()->after('author');
            }
            if (!Schema::hasColumn('articles', 'image_color')) {
                $table->string('image_color')->nullable()->after('image');
            }
            if (!Schema::hasColumn('articles', 'reading_time')) {
                $table->integer('reading_time')->nullable()->after('view_count');
            }
            if (!Schema::hasColumn('articles', 'tags')) {
                $table->string('tags')->nullable()->after('author_bio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['author_bio', 'image_color', 'reading_time']);
        });
    }
};

