<?php

use App\Http\Controllers\IotSensorController;
use App\Http\Controllers\MapController;
use App\Services\PythonKKExtractorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/iot-sensors', [IotSensorController::class, 'store']);
Route::get('/map/locations', [MapController::class, 'getLocations']);

// KK Extraction API - Python Backend
Route::post('/extract-kk', function (Request $request) {
    $request->validate([
        'pdf' => 'required|file|mimes:pdf|max:10240',
        'use_mapping' => 'sometimes|boolean'
    ]);

    try {
        $pdfFile = $request->file('pdf');
        $useMapping = $request->boolean('use_mapping', false);

        $tempPath = $pdfFile->store('temp_kk_pdfs');
        $absolutePath = Storage::path($tempPath);

        $service = app(PythonKKExtractorService::class);
        $data = $service->extractFromPDF($absolutePath, $useMapping);

        Storage::delete($tempPath);

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => count($data),
            'file' => $pdfFile->getClientOriginalName()
        ]);

    } catch (\Exception $e) {
        if (isset($tempPath)) {
            Storage::delete($tempPath);
        }

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/extract-kk/check-dependencies', function () {
    $service = app(PythonKKExtractorService::class);
    $status = $service->checkDependencies();

    return response()->json([
        'success' => true,
        'dependencies' => $status
    ]);
});
