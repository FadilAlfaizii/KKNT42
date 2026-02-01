<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\WithFileUploads;
use App\Services\KKExtraction\KKExtractorService;
use App\Services\KKExtraction\KKImportService;
use App\Models\KKExtractionHistory;
use Filament\Notifications\Notification;

class EkstraksiKartuKeluarga extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.ekstraksi-kartu-keluarga';
    protected static ?string $navigationGroup = 'Kependudukan';
    protected static ?string $navigationLabel = 'Ekstraksi KK';
    protected static ?string $title = 'Ekstraksi Kartu Keluarga';
    protected static ?int $navigationSort = 3;

    // Form properties
    public string $extractionMode = 'manual'; // 'gemini' or 'manual'
    public string $geminiApiKey = '';
    public $uploadedFiles = [];
    public bool $useMapping = false; // Toggle untuk mapping ID
    
    // State properties
    public bool $isProcessing = false;
    public int $progressPercentage = 0;
    public string $progressMessage = '';
    public array $extractedData = [];
    public int $totalRecords = 0;
    public array $extractionSummary = []; // Summary statistics
    public array $uploadedFileNames = []; // Track uploaded file names
    public array $duplicateInfo = []; // Duplicate detection results
    public bool $showDuplicateModal = false;
    public string $excelFilePath = ''; // Store Excel path for history

    public function mount(): void
    {
        // Load Gemini API key from env if available
        $this->geminiApiKey = config('services.gemini.api_key', '');
    }
    
    // Computed property untuk menampilkan status mapping di UI
    public function getUseMappingLabelProperty(): string
    {
        return $this->useMapping ? 'Output: Angka ID' : 'Output: Teks Mentah';
    }

    public function updatedUploadedFiles()
    {
        // Track uploaded file names
        $this->uploadedFileNames = [];
        foreach ($this->uploadedFiles as $file) {
            $this->uploadedFileNames[] = [
                'name' => $file->getClientOriginalName(),
                'size' => $this->formatBytes($file->getSize())
            ];
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function extract(): void
    {
        // Validate
        if (empty($this->uploadedFiles)) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Silakan upload file PDF terlebih dahulu.')
                ->send();
            return;
        }

        if ($this->extractionMode === 'gemini' && empty($this->geminiApiKey)) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('API Key Gemini diperlukan untuk mode AI.')
                ->send();
            return;
        }

        $this->isProcessing = true;
        $this->progressPercentage = 0;
        $this->progressMessage = 'Memulai proses ekstraksi...';
        $this->extractedData = [];
        $this->extractionSummary = [];

        try {
            $this->progressPercentage = 10;
            $this->progressMessage = 'Memuat file PDF...';
            $this->dispatch('progress-updated', ['percent' => 10]);
            
            sleep(1); // Give UI time to update
            
            $extractor = new KKExtractorService();
            
            $this->progressPercentage = 30;
            $this->progressMessage = 'Memproses ekstraksi data...';
            $this->dispatch('progress-updated', ['percent' => 30]);
            
            sleep(1);
            
            // Process extraction based on mode (now returns array with 'data' and 'summary')
            if ($this->extractionMode === 'gemini') {
                $this->progressMessage = 'Menggunakan AI Gemini untuk OCR...';
                $result = $extractor->extractGemini($this->uploadedFiles, $this->geminiApiKey, $this->useMapping);
            } else {
                $this->progressMessage = 'Parsing data dengan parser manual...';
                $result = $extractor->extractManual($this->uploadedFiles, $this->useMapping);
            }

            $this->progressPercentage = 70;
            $this->progressMessage = 'Validasi dan normalisasi data...';
            $this->dispatch('progress-updated', ['percent' => 70]);
            
            sleep(1);

            $this->extractedData = $result['data'];
            $this->extractionSummary = $result['summary'];
            $this->totalRecords = count($this->extractedData);
            
            // Save to history after successful extraction
            $this->saveExtractionHistory('completed');
            
            $this->progressPercentage = 90;
            $this->progressMessage = 'Menyelesaikan proses...';
            $this->dispatch('progress-updated', ['percent' => 90]);
            
            sleep(1);

            $this->progressPercentage = 100;
            $this->progressMessage = 'Selesai!';
            $this->dispatch('progress-updated', ['percent' => 100]);
            
            if ($this->totalRecords === 0) {
                Notification::make()
                    ->warning()
                    ->title('Tidak Ada Data')
                    ->body('Tidak ada data yang berhasil diekstrak dari file PDF.')
                    ->send();
            } else {
                $summary = $this->extractionSummary;
                $notifBody = "✓ {$summary['total_people']} orang dari {$summary['successful_files']} file";
                
                if ($this->useMapping) {
                    $notifBody .= " | Kualitas: {$summary['ok_count']} OK ({$summary['ok_percentage']}%), {$summary['flagged_count']} perlu cek ({$summary['flagged_percentage']}%)";
                }
                
                if ($summary['nik_errors_count'] > 0) {
                    $notifBody .= " | ⚠ {$summary['nik_errors_count']} NIK invalid";
                }
                
                Notification::make()
                    ->success()
                    ->title('Berhasil!')
                    ->body($notifBody)
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->send();
            
            \Log::error('Extraction error: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
            $this->progressPercentage = 0;
            $this->progressMessage = '';
        }
    }

    public function downloadExcel()
    {
        if (empty($this->extractedData)) {
            Notification::make()
                ->warning()
                ->title('Tidak Ada Data')
                ->body('Tidak ada data untuk diexport.')
                ->send();
            return;
        }

        try {
            $extractor = new KKExtractorService();
            $filePath = $extractor->generateExcel($this->extractedData, $this->useMapping);
            
            // Store path for history
            $this->excelFilePath = $filePath;
            
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Gagal membuat file Excel: ' . $e->getMessage())
                ->send();
        }
    }

    public function resetForm(): void
    {
        $this->reset(['uploadedFiles', 'extractedData', 'totalRecords', 'extractionSummary']);
        
        Notification::make()
            ->info()
            ->title('Reset')
            ->body('Form telah direset.')
            ->send();
    }

    /**
     * Check for duplicates before import
     */
    public function checkDuplicates(): void
    {
        if (empty($this->extractedData)) {
            Notification::make()
                ->warning()
                ->title('Tidak Ada Data')
                ->body('Tidak ada data untuk diperiksa.')
                ->send();
            return;
        }

        try {
            $importService = new KKImportService();
            $this->duplicateInfo = $importService->checkDuplicates($this->extractedData);
            
            $summary = $this->duplicateInfo['summary'];
            
            if ($summary['total_duplicates'] > 0) {
                $this->showDuplicateModal = true;
                
                $message = "Ditemukan {$summary['total_duplicates']} duplikat NIK:\n";
                $message .= "• {$summary['batch_duplicates']} dalam batch ini\n";
                $message .= "• {$summary['db_duplicates']} sudah ada di database";
                
                Notification::make()
                    ->warning()
                    ->title('⚠ Duplikat Ditemukan')
                    ->body($message)
                    ->persistent()
                    ->send();
            } else {
                // No duplicates, proceed to import
                $this->importToDatabase(false);
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Gagal memeriksa duplikat: ' . $e->getMessage())
                ->send();
        }
    }

    /**
     * Import extracted data to database
     */
    public function importToDatabase(bool $skipDuplicates = false): void
    {
        if (empty($this->extractedData)) {
            Notification::make()
                ->warning()
                ->title('Tidak Ada Data')
                ->body('Tidak ada data untuk diimpor.')
                ->send();
            return;
        }

        $this->isProcessing = true;
        $this->progressMessage = 'Mengimpor data ke database...';

        try {
            $importService = new KKImportService();
            $results = $importService->importToDatabase($this->extractedData, $skipDuplicates);

            if ($results['success']) {
                // Update history with import info
                $this->saveExtractionHistory('imported', [
                    'imported_kk' => $results['imported_kk'],
                    'imported_penduduk' => $results['imported_penduduk'],
                    'duplicates_found' => $this->duplicateInfo['summary']['total_duplicates'] ?? 0,
                ]);

                $message = "✓ Berhasil mengimpor:\n";
                $message .= "• {$results['imported_kk']} Keluarga (KK)\n";
                $message .= "• {$results['imported_penduduk']} Penduduk";
                
                if ($results['skipped'] > 0) {
                    $message .= "\n⚠ {$results['skipped']} data dilewati";
                }
                
                Notification::make()
                    ->success()
                    ->title('Import Berhasil!')
                    ->body($message)
                    ->persistent()
                    ->send();
                
                $this->showDuplicateModal = false;
            } else {
                $errorMessage = "Import gagal:\n" . implode("\n", array_slice($results['errors'], 0, 3));
                
                Notification::make()
                    ->danger()
                    ->title('Import Gagal')
                    ->body($errorMessage)
                    ->persistent()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Terjadi kesalahan saat import: ' . $e->getMessage())
                ->send();
            
            \Log::error('Import error: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
            $this->progressMessage = '';
        }
    }

    /**
     * Skip duplicates and import
     */
    public function importSkipDuplicates(): void
    {
        $this->importToDatabase(true);
    }

    /**
     * Cancel import
     */
    public function cancelImport(): void
    {
        $this->showDuplicateModal = false;
        
        Notification::make()
            ->info()
            ->title('Dibatalkan')
            ->body('Import dibatalkan.')
            ->send();
    }

    /**
     * Save extraction to history
     */
    private function saveExtractionHistory(string $status, array $additionalData = []): void
    {
        try {
            $summary = $this->extractionSummary;
            
            $historyData = [
                'user_id' => auth()->id(),
                'extraction_mode' => $this->extractionMode,
                'use_mapping' => $this->useMapping,
                'total_files' => $summary['total_files'] ?? 0,
                'successful_files' => $summary['successful_files'] ?? 0,
                'failed_files' => $summary['failed_files'] ?? 0,
                'total_people' => $summary['total_people'] ?? 0,
                'imported_kk' => $additionalData['imported_kk'] ?? 0,
                'imported_penduduk' => $additionalData['imported_penduduk'] ?? 0,
                'duplicates_found' => $additionalData['duplicates_found'] ?? 0,
                'summary_stats' => $summary,
                'file_names' => array_column($this->uploadedFileNames, 'name'),
                'error_details' => $summary['errors'] ?? [],
                'excel_file_path' => $this->excelFilePath,
                'status' => $status,
            ];

            KKExtractionHistory::create($historyData);
            
        } catch (\Exception $e) {
            \Log::error('Failed to save extraction history: ' . $e->getMessage());
        }
    }
}
