<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if columns exist before adding
        $columns = DB::select('PRAGMA table_info(articles)');
        $existingColumns = array_column($columns, 'name');
        
        // Add columns directly using raw SQL for SQLite only if they don't exist
        if (!in_array('author_bio', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN author_bio VARCHAR DEFAULT NULL');
        }
        if (!in_array('image_color', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN image_color VARCHAR DEFAULT NULL');
        }
        if (!in_array('reading_time', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN reading_time INTEGER DEFAULT NULL');
        }
        if (!in_array('tags', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN tags VARCHAR DEFAULT NULL');
        }
        if (!in_array('is_published', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN is_published TINYINT(1) DEFAULT 0');
        }
        if (!in_array('published_at', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN published_at DATETIME DEFAULT NULL');
        }
        if (!in_array('view_count', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN view_count INTEGER DEFAULT 0');
        }
        if (!in_array('slug', $existingColumns)) {
            DB::statement('ALTER TABLE articles ADD COLUMN slug VARCHAR UNIQUE DEFAULT NULL');
        }
    }

    public function down(): void
    {
        // SQLite doesn't support DROP COLUMN in older versions, so we'll recreate the table
        // This is a destructive operation - only use in development
    }
};

