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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarga_id')->constrained('keluargas')->onDelete('cascade');
            $table->foreignId('dusun_id')->nullable()->constrained('dusuns')->onDelete('cascade');
            $table->string('nik', 16)->unique(); // Nomor Induk Kependudukan
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['LAKI-LAKI', 'PEREMPUAN']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('umur')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('status_perkawinan', ['BELUM KAWIN', 'KAWIN', 'CERAI HIDUP', 'CERAI MATI'])->nullable();
            $table->enum('status_dalam_keluarga', ['KEPALA KELUARGA', 'SUAMI', 'ISTRI', 'ANAK', 'MENANTU', 'CUCU', 'ORANGTUA', 'MERTUA', 'FAMILI LAIN', 'PEMBANTU', 'LAINNYA'])->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('kewarganegaraan')->default('WNI');
            $table->string('no_paspor')->nullable();
            $table->string('no_akta_lahir')->nullable();
            $table->enum('status_penduduk', ['TETAP', 'TIDAK TETAP', 'PENDATANG', 'MENINGGAL', 'PINDAH'])->default('TETAP');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
