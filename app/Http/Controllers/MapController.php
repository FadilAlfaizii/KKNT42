<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Sekolah;
use Inertia\Inertia;
use Inertia\Response;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $sekolah = Sekolah::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'location' => $item->location,
                'description' => $item->description,
                'image_url' => $item->image_url ? asset('storage/' . $item->image_url) : null,
                'latitude' => (float) $item->latitude,
                'longitude' => (float) $item->longitude,
            ];
        });

        return Inertia::render('Geodeskel', [
            'fasum' => [],
            'sekolah' => $sekolah,
            'tempat_ibadah' => [],
            'lokasi_usaha' => [],
            'fasilitas_kesehatan' => [],
            'pertanian' => [],
            'perikanan' => [],
            'perkebunan' => [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Map $map): Response
    {
        return Inertia::render('DetailGeodeskel', [
            'map' => $map,
        ]);
    }
}
