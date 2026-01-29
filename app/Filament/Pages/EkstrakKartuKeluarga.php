<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Services\PythonKKExtractorService;
use Filament\Notifications\Notification;

class EkstrakKartuKeluarga extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.ekstrak-kartu-keluarga';

    protected static ?string $navigationLabel = 'Ekstrak Kartu Keluarga';

    protected static ?string $title = 'Ekstrak Data Kartu Keluarga';

    protected static ?string $slug = 'ekstrak-kartu-keluarga';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 50;

    public function getHeading(): string
    {
        return 'Ekstrak Data Kartu Keluarga';
    }

    public function getSubheading(): string | null
    {
        return 'Ekstrak data otomatis dari file PDF Kartu Keluarga menggunakan Python Parser (100% Akurat)';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('checkDependencies')
                ->label('Check Python Setup')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('gray')
                ->action(function () {
                    $service = app(PythonKKExtractorService::class);
                    $status = $service->checkDependencies();
                    
                    $messages = [];
                    $messages[] = '**Python:** ' . ($status['python_available'] ? '✅ ' . $status['python_version'] : '❌ Not found');
                    $messages[] = '**pypdf:** ' . ($status['pypdf_available'] ? '✅ Installed' : '❌ Not installed');
                    $messages[] = '**Script:** ' . ($status['script_exists'] ? '✅ Found' : '❌ Missing');
                    
                    $allGood = $status['python_available'] && $status['pypdf_available'] && $status['script_exists'];
                    
                    Notification::make()
                        ->title('Python Dependencies Status')
                        ->body(implode("\n\n", $messages))
                        ->icon($allGood ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle')
                        ->color($allGood ? 'success' : 'warning')
                        ->persistent()
                        ->send();
                }),
        ];
    }
}
