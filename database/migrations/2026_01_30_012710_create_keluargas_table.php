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
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dusun_id')->nullable()->constrained('dusuns')->onDelete('cascade');
            $table->string('no_kk', 16)->unique(); // Nomor Kartu Keluarga
            $table->string('kepala_keluarga'); // Nama Kepala Keluarga
            $table->string('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan_desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->enum('status_kk', ['AKTIF', 'TIDAK AKTIF'])->default('AKTIF');
            $table->date('tanggal_terbit')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
