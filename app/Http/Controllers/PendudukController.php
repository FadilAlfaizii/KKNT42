<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Inertia\Inertia;
use Inertia\Response;

class PendudukController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PendudukDashboard', [
            'total' => 0,
            'laki_laki' => 0,
            'perempuan' => 0,
            'kk' => 0,
        ]);
    }
}