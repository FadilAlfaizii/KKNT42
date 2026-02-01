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
        Schema::create('kk_extraction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('extraction_mode'); // 'manual' or 'gemini'
            $table->boolean('use_mapping')->default(false);
            $table->integer('total_files');
            $table->integer('successful_files');
            $table->integer('failed_files')->default(0);
            $table->integer('total_people');
            $table->integer('imported_kk')->default(0);
            $table->integer('imported_penduduk')->default(0);
            $table->integer('duplicates_found')->default(0);
            $table->json('summary_stats')->nullable(); // Store full summary
            $table->json('file_names')->nullable(); // Array of file names
            $table->json('error_details')->nullable(); // Details of errors
            $table->string('excel_file_path')->nullable(); // Path to generated Excel
            $table->enum('status', ['completed', 'failed', 'imported', 'partial'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kk_extraction_histories');
    }
};
