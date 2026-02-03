<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Filament\Resources\KeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenduduk extends CreateRecord
{
    protected static string $resource = PendudukResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-calculate umur if tanggal_lahir is set
        if (!empty($data['tanggal_lahir'])) {
            $tanggalLahir = \Carbon\Carbon::parse($data['tanggal_lahir']);
            $data['umur'] = $tanggalLahir->age;
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        // Redirect back to Keluarga view if came from there
        $keluargaId = request()->query('keluarga_id');
        if ($keluargaId) {
            return KeluargaResource::getUrl('index');
        }
        
        return $this->getResource()::getUrl('index');
    }
}
