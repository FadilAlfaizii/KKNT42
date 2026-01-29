<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

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
        return 'Ekstrak data otomatis dari file PDF Kartu Keluarga menggunakan AI';
    }
}
