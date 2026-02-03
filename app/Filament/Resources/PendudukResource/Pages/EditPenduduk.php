<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-update umur if tanggal_lahir changed
        if (!empty($data['tanggal_lahir'])) {
            $tanggalLahir = \Carbon\Carbon::parse($data['tanggal_lahir']);
            $data['umur'] = $tanggalLahir->age;
        }
        
        return $data;
    }
}
