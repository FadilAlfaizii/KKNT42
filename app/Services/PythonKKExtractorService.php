<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class PythonKKExtractorService
{
    protected string $pythonPath;
    protected string $scriptPath;

    public function __construct()
    {
        // Detect Python executable (python3 or python)
        $this->pythonPath = $this->detectPython();
        $this->scriptPath = base_path('app/Scripts/extract_kk.py');
    }

    /**
     * Detect available Python executable
     */
    protected function detectPython(): string
    {
        // Try python3 first (Linux/Mac)
        exec('which python3 2>&1', $output, $returnCode);
        if ($returnCode === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        // Fallback to python (Windows/Some Linux)
        exec('which python 2>&1', $output2, $returnCode2);
        if ($returnCode2 === 0 && !empty($output2[0])) {
            return trim($output2[0]);
        }

        // Default fallback
        return 'python3';
    }

    /**
     * Extract KK data from PDF file using Python script
     *
     * @param string $pdfPath Absolute path to PDF file
     * @param bool $useMapping Whether to apply ID mapping
     * @return array
     * @throws Exception
     */
    public function extractFromPDF(string $pdfPath, bool $useMapping = false): array
    {
        // Validate file exists
        if (!file_exists($pdfPath)) {
            throw new Exception("PDF file not found: {$pdfPath}");
        }

        // Validate Python script exists
        if (!file_exists($this->scriptPath)) {
            throw new Exception("Python script not found: {$this->scriptPath}");
        }

        // Build command
        $command = sprintf(
            '%s %s %s %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($this->scriptPath),
            escapeshellarg($pdfPath),
            $useMapping ? '--mapping' : ''
        );

        Log::info('Executing Python KK extractor', [
            'command' => $command,
            'pdf_path' => $pdfPath,
            'use_mapping' => $useMapping
        ]);

        // Execute command
        exec($command, $output, $returnCode);

        // Join output
        $jsonOutput = implode("\n", $output);

        // Log raw output for debugging
        Log::debug('Python script output', [
            'output' => $jsonOutput,
            'return_code' => $returnCode
        ]);

        // Parse JSON response
        $result = json_decode($jsonOutput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to parse Python script output', [
                'json_error' => json_last_error_msg(),
                'output' => $jsonOutput
            ]);
            throw new Exception('Failed to parse Python script output: ' . json_last_error_msg());
        }

        // Check if extraction was successful
        if (!isset($result['success']) || $result['success'] !== true) {
            $errorMsg = $result['error'] ?? 'Unknown error occurred';
            Log::error('Python extraction failed', ['error' => $errorMsg]);
            throw new Exception('Extraction failed: ' . $errorMsg);
        }

        return $result['data'] ?? [];
    }

    /**
     * Extract KK data from multiple PDF files
     *
     * @param array $pdfPaths Array of absolute paths to PDF files
     * @param bool $useMapping Whether to apply ID mapping
     * @return array
     */
    public function extractFromMultiplePDFs(array $pdfPaths, bool $useMapping = false): array
    {
        $results = [];

        foreach ($pdfPaths as $pdfPath) {
            try {
                $data = $this->extractFromPDF($pdfPath, $useMapping);
                $results[] = [
                    'file' => basename($pdfPath),
                    'success' => true,
                    'data' => $data,
                    'count' => count($data)
                ];
            } catch (Exception $e) {
                $results[] = [
                    'file' => basename($pdfPath),
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Check if Python and required packages are available
     *
     * @return array
     */
    public function checkDependencies(): array
    {
        $status = [
            'python_available' => false,
            'python_version' => null,
            'pypdf_available' => false,
            'pandas_available' => false,
            'script_exists' => file_exists($this->scriptPath),
        ];

        // Check Python
        exec("{$this->pythonPath} --version 2>&1", $pythonOutput, $pythonCode);
        if ($pythonCode === 0) {
            $status['python_available'] = true;
            $status['python_version'] = trim($pythonOutput[0] ?? '');
        }

        // Check pypdf
        exec("{$this->pythonPath} -c \"import pypdf\" 2>&1", $pypdfOutput, $pypdfCode);
        $status['pypdf_available'] = ($pypdfCode === 0);

        // Check pandas (optional, not used in script but good to have)
        exec("{$this->pythonPath} -c \"import pandas\" 2>&1", $pandasOutput, $pandasCode);
        $status['pandas_available'] = ($pandasCode === 0);

        return $status;
    }

    /**
     * Get Python executable path
     *
     * @return string
     */
    public function getPythonPath(): string
    {
        return $this->pythonPath;
    }
}
