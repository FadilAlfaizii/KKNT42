<?php

namespace App\Http\Controllers;

use App\Models\IotSensors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class IotSensorController extends Controller
{
    /**
     * Store IoT sensor data from devices
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Comprehensive validation with realistic ranges
            $validated = $request->validate([
                'farm_id' => 'required|uuid|exists:farms,id',
                'temperature' => 'required|numeric|min:-50|max:60',
                'humidity' => 'required|numeric|min:0|max:100',
                'ammonia' => 'required|numeric|min:0|max:1000',
                'light_intensity' => 'required|numeric|min:0|max:200000',
            ]);

            // Create sensor record
            $sensor = IotSensors::create($validated);

            // Log successful storage
            Log::info('IoT sensor data stored successfully', [
                'farm_id' => $validated['farm_id'],
                'sensor_id' => $sensor->id,
                'timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sensor berhasil disimpan',
                'data' => $sensor,
            ], 201);

        } catch (ValidationException $e) {
            // Log validation failure
            Log::warning('IoT sensor validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['farm_id']),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('IoT sensor storage failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data sensor',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
