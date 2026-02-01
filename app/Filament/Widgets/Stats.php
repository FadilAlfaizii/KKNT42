<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Article;
use App\Models\Keluarga;
use App\Models\Penduduk;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Jumlah pengguna terdaftar')
                ->icon('heroicon-o-user')
                ->color('success'),

            Stat::make('Total Keluarga', Keluarga::count())
                ->description('Jumlah KK terdaftar')
                ->icon('heroicon-o-home')
                ->color('primary'),

            Stat::make('Total Penduduk', Penduduk::count())
                ->description('Jumlah penduduk')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Total Artikel', Article::count())
                ->description('Jumlah artikel & berita')
                ->icon('heroicon-o-newspaper')
                ->color('warning'),
        ];
    }
}
