<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class ExportHelper
{
    public static function exportPendudukToExcel(Collection $penduduks): string
    {
        $headers = [
            'ID',
            'Dusun',
            'No. KK',
            'NIK',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Agama',
            'Pendidikan',
            'Pekerjaan',
            'Status Perkawinan',
            'Status Dalam Keluarga',
            'Nama Ayah',
            'Nama Ibu',
            'Golongan Darah',
            'Kewarganegaraan',
            'Status Penduduk',
            'Alamat Sebelumnya',
            'Dibuat Pada',
        ];

        $csv = implode(',', array_map(function ($header) {
            return '"' . str_replace('"', '""', $header) . '"';
        }, $headers)) . "\n";

        foreach ($penduduks as $penduduk) {
            $row = [
                $penduduk->id,
                $penduduk->dusun?->name ?? '',
                $penduduk->keluarga?->no_kk ?? '',
                $penduduk->nik ?? '',
                $penduduk->nama_lengkap ?? '',
                $penduduk->jenis_kelamin ?? '',
                $penduduk->tempat_lahir ?? '',
                $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d/m/Y') : '',
                $penduduk->umur ?? '',
                $penduduk->agama ?? '',
                $penduduk->pendidikan ?? '',
                $penduduk->pekerjaan ?? '',
                $penduduk->status_perkawinan ?? '',
                $penduduk->status_dalam_keluarga ?? '',
                $penduduk->nama_ayah ?? '',
                $penduduk->nama_ibu ?? '',
                $penduduk->golongan_darah ?? '',
                $penduduk->kewarganegaraan ?? '',
                $penduduk->status_penduduk ?? '',
                $penduduk->alamat_sebelumnya ?? '',
                $penduduk->created_at ? $penduduk->created_at->format('d/m/Y H:i') : '',
            ];

            $csv .= implode(',', array_map(function ($field) {
                return '"' . str_replace('"', '""', (string) $field) . '"';
            }, $row)) . "\n";
        }

        return $csv;
    }

    public static function exportKeluargaToExcel(Collection $keluargas): string
    {
        $headers = [
            'ID',
            'Dusun',
            'No. KK',
            'Kepala Keluarga',
            'Alamat',
            'RT',
            'RW',
            'Kelurahan/Desa',
            'Kecamatan',
            'Kabupaten/Kota',
            'Provinsi',
            'Kode Pos',
            'Jumlah Anggota',
            'Status KK',
            'Tanggal Terbit',
            'Keterangan',
            'Dibuat Pada',
        ];

        $csv = implode(',', array_map(function ($header) {
            return '"' . str_replace('"', '""', $header) . '"';
        }, $headers)) . "\n";

        foreach ($keluargas as $keluarga) {
            $row = [
                $keluarga->id,
                $keluarga->dusun?->name ?? '',
                $keluarga->no_kk ?? '',
                $keluarga->kepala_keluarga ?? '',
                $keluarga->alamat ?? '',
                $keluarga->rt ?? '',
                $keluarga->rw ?? '',
                $keluarga->kelurahan_desa ?? '',
                $keluarga->kecamatan ?? '',
                $keluarga->kabupaten_kota ?? '',
                $keluarga->provinsi ?? '',
                $keluarga->kode_pos ?? '',
                $keluarga->penduduks_count ?? $keluarga->penduduks()->count(),
                $keluarga->status_kk ?? '',
                $keluarga->tanggal_terbit ? \Carbon\Carbon::parse($keluarga->tanggal_terbit)->format('d/m/Y') : '',
                $keluarga->keterangan ?? '',
                $keluarga->created_at ? $keluarga->created_at->format('d/m/Y H:i') : '',
            ];

            $csv .= implode(',', array_map(function ($field) {
                return '"' . str_replace('"', '""', (string) $field) . '"';
            }, $row)) . "\n";
        }

        return $csv;
    }
}
