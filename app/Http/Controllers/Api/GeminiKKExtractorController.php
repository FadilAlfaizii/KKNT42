<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiKKExtractorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiKKExtractorController extends Controller
{
    private GeminiKKExtractorService $geminiService;

    public function __construct(GeminiKKExtractorService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Extract data dari PDF Kartu Keluarga menggunakan Gemini AI
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function extract(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            ]);

            $file = $request->file('pdf');
            $fileName = $file->getClientOriginalName();

            // Save temporary file
            $tempPath = $file->store('temp/kk', 'local');
            $fullPath = Storage::disk('local')->path($tempPath);

            Log::info('Gemini KK Extraction Started', [
                'file' => $fileName,
                'size' => $file->getSize(),
            ]);

            // Extract menggunakan Gemini AI
            $extractedData = $this->geminiService->extractPdf($fullPath);

            // Delete temporary file
            Storage::disk('local')->delete($tempPath);

            Log::info('Gemini KK Extraction Success', [
                'file' => $fileName,
                'records' => count($extractedData),
            ]);

            return response()->json([
                'success' => true,
                'fileName' => pathinfo($fileName, PATHINFO_FILENAME),
                'data' => $extractedData,
                'fullData' => $extractedData, // Untuk kompatibilitas dengan frontend
                'recordCount' => count($extractedData),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'File tidak valid. Pastikan file berformat PDF dan ukuran maksimal 10MB.',
                'details' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Gemini KK Extraction Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal mengekstrak data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Health check endpoint untuk Gemini service
     * 
     * @return JsonResponse
     */
    public function healthCheck(): JsonResponse
    {
        try {
            $apiKey = env('GEMINI_API_KEY');
            
            return response()->json([
                'success' => true,
                'service' => 'Gemini KK Extractor',
                'apiKeyConfigured' => !empty($apiKey),
                'model' => 'gemini-2.0-flash-exp',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
