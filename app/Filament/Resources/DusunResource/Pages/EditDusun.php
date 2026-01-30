<?php

namespace App\Filament\Resources\DusunResource\Pages;

use App\Filament\Resources\DusunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDusun extends EditRecord
{
    protected static string $resource = DusunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
