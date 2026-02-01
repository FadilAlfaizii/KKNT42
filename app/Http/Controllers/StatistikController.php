<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\MapPoint;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatistikController extends Controller
{
    public function index()
    {
        // Total counts
        $totalPenduduk = Penduduk::count();
        $totalKK = Keluarga::count();
        $lakiLaki = Penduduk::where('jenis_kelamin', 'LAKI-LAKI')->count();
        $perempuan = Penduduk::where('jenis_kelamin', 'PEREMPUAN')->count();

        // 1. Distribusi Usia (Histogram)
        $distribusiUsia = $this->getDistribusiUsia();

        // 2. Pekerjaan (Pie Chart)
        $pekerjaan = $this->getPekerjaanStats();

        // 3. Pendidikan (Pie Chart)
        $pendidikan = $this->getPendidikanStats();

        // 4. Golongan Darah (Pie Chart)
        $golonganDarah = $this->getGolonganDarahStats();

        // 5. Agama (Pie Chart)
        $agama = $this->getAgamaStats();

        // 6. Jenis Kelamin (Bar Chart)
        $jenisKelamin = [
            ['label' => 'Laki-laki', 'count' => $lakiLaki],
            ['label' => 'Perempuan', 'count' => $perempuan],
        ];

        // 7. Kategori Titik Lokasi (Bar Chart)
        $kategoriLokasi = MapPoint::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $this->translateCategory($item->category),
                    'count' => $item->count
                ];
            });

        // Statistik Tambahan
        $statistikTambahan = [
            'totalDusun' => class_exists('\App\Models\Dusun') ? \App\Models\Dusun::count() : 0,
            'averagePerKK' => $totalKK > 0 ? round($totalPenduduk / $totalKK, 2) : 0,
            'totalArtikel' => class_exists('\App\Models\Article') ? \App\Models\Article::published()->count() : 0,
            'totalLokasiPenting' => MapPoint::count(),
        ];

        return Inertia::render('Statistik', [
            'statistics' => [
                'totalPenduduk' => $totalPenduduk,
                'totalKK' => $totalKK,
                'lakiLaki' => $lakiLaki,
                'perempuan' => $perempuan,
                'distribusiUsia' => $distribusiUsia,
                'pekerjaan' => $pekerjaan,
                'pendidikan' => $pendidikan,
                'golonganDarah' => $golonganDarah,
                'agama' => $agama,
                'jenisKelamin' => $jenisKelamin,
                'kategoriLokasi' => $kategoriLokasi,
                'tambahan' => $statistikTambahan,
            ]
        ]);
    }

    private function getDistribusiUsia()
    {
        $penduduk = Penduduk::whereNotNull('tanggal_lahir')->get();
        
        $ranges = [
            '0-4' => 0,
            '5-9' => 0,
            '10-14' => 0,
            '15-19' => 0,
            '20-24' => 0,
            '25-29' => 0,
            '30-34' => 0,
            '35-39' => 0,
            '40-44' => 0,
            '45-49' => 0,
            '50-54' => 0,
            '55-59' => 0,
            '60-64' => 0,
            '65-69' => 0,
            '70+' => 0,
        ];

        foreach ($penduduk as $p) {
            try {
                $tanggalLahir = \Carbon\Carbon::parse($p->tanggal_lahir);
                $usia = $tanggalLahir->age;
                
                if ($usia <= 4) $ranges['0-4']++;
                elseif ($usia <= 9) $ranges['5-9']++;
                elseif ($usia <= 14) $ranges['10-14']++;
                elseif ($usia <= 19) $ranges['15-19']++;
                elseif ($usia <= 24) $ranges['20-24']++;
                elseif ($usia <= 29) $ranges['25-29']++;
                elseif ($usia <= 34) $ranges['30-34']++;
                elseif ($usia <= 39) $ranges['35-39']++;
                elseif ($usia <= 44) $ranges['40-44']++;
                elseif ($usia <= 49) $ranges['45-49']++;
                elseif ($usia <= 54) $ranges['50-54']++;
                elseif ($usia <= 59) $ranges['55-59']++;
                elseif ($usia <= 64) $ranges['60-64']++;
                elseif ($usia <= 69) $ranges['65-69']++;
                else $ranges['70+']++;
            } catch (\Exception $e) {
                continue;
            }
        }

        return collect($ranges)->map(function ($count, $range) {
            return ['label' => $range . ' tahun', 'count' => $count];
        })->values();
    }

    private function getPekerjaanStats()
    {
        return Penduduk::select('pekerjaan', DB::raw('count(*) as count'))
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->groupBy('pekerjaan')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucwords(strtolower(str_replace('_', ' ', $item->pekerjaan))),
                    'count' => $item->count
                ];
            });
    }

    private function getPendidikanStats()
    {
        return Penduduk::select('pendidikan', DB::raw('count(*) as count'))
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '!=', '')
            ->groupBy('pendidikan')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucwords(strtolower(str_replace('_', ' ', $item->pendidikan))),
                    'count' => $item->count
                ];
            });
    }

    private function getGolonganDarahStats()
    {
        return Penduduk::select('golongan_darah', DB::raw('count(*) as count'))
            ->whereNotNull('golongan_darah')
            ->where('golongan_darah', '!=', '')
            ->where('golongan_darah', '!=', '-')
            ->groupBy('golongan_darah')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => strtoupper($item->golongan_darah),
                    'count' => $item->count
                ];
            });
    }

    private function getAgamaStats()
    {
        return Penduduk::select('agama', DB::raw('count(*) as count'))
            ->whereNotNull('agama')
            ->where('agama', '!=', '')
            ->where('agama', '!=', '-')
            ->groupBy('agama')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucfirst(strtolower($item->agama)),
                    'count' => $item->count
                ];
            });
    }

    private function translateCategory($category)
    {
        $translations = [
            'office' => 'Kantor Pemerintahan',
            'education' => 'Pendidikan',
            'health' => 'Kesehatan',
            'worship' => 'Tempat Ibadah',
            'commercial' => 'Komersial',
            'recreation' => 'Rekreasi',
            'agriculture' => 'Pertanian',
            'other' => 'Lainnya',
        ];

        return $translations[$category] ?? ucfirst($category);
    }
}
