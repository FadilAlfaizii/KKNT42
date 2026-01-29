<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\PythonKKExtractorService;
use Illuminate\Support\Facades\Storage;

Route::prefix('api')->group(function () {
    /**
     * Extract KK data from uploaded PDF using Python backend
     */
    Route::post('/extract-kk', function (Request $request) {
        // Validate request
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'use_mapping' => 'sometimes|boolean'
        ]);

        try {
            $pdfFile = $request->file('pdf');
            $useMapping = $request->input('use_mapping', false);

            // Store file temporarily
            $tempPath = $pdfFile->store('temp_kk_pdfs');
            $absolutePath = Storage::path($tempPath);

            // Extract data using Python service
            $service = app(PythonKKExtractorService::class);
            $data = $service->extractFromPDF($absolutePath, $useMapping);

            // Clean up temporary file
            Storage::delete($tempPath);

            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
                'file' => $pdfFile->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            // Clean up on error
            if (isset($tempPath)) {
                Storage::delete($tempPath);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * Check Python dependencies status
     */
    Route::get('/extract-kk/check-dependencies', function () {
        $service = app(PythonKKExtractorService::class);
        $status = $service->checkDependencies();

        return response()->json([
            'success' => true,
            'dependencies' => $status
        ]);
    });
});
