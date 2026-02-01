<?php

namespace App\Filament\Resources\KKExtractionHistoryResource\Pages;

use App\Filament\Resources\KKExtractionHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKKExtractionHistories extends ListRecords
{
    protected static string $resource = KKExtractionHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
