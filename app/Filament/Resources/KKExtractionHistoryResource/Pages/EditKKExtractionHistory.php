<?php

namespace App\Filament\Resources\KKExtractionHistoryResource\Pages;

use App\Filament\Resources\KKExtractionHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKKExtractionHistory extends EditRecord
{
    protected static string $resource = KKExtractionHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
