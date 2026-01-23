<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Article;
use App\Models\IotSensors;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Jumlah pengguna terdaftar')
                ->icon('heroicon-o-user')
                ->color('success'),

            Stat::make('Total Artikel', Article::count())
                ->description('Jumlah artikel & berita')
                ->icon('heroicon-o-newspaper')
                ->color('primary'),

            Stat::make('Sensor IoT Aktif', IotSensors::count())
                ->description('Jumlah sensor aktif')
                ->icon('heroicon-o-cpu-chip')
                ->color('warning'),
        ];
    }
}
