<?php

namespace App\Filament\Resources\KKExtractionHistoryResource\Pages;

use App\Filament\Resources\KKExtractionHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;

class ViewKKExtractionHistory extends ViewRecord
{
    protected static string $resource = KKExtractionHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->label('Download Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn ($record) => !empty($record->excel_file_path) && file_exists($record->excel_file_path))
                ->action(function ($record) {
                    return response()->download($record->excel_file_path);
                }),
            Actions\EditAction::make()->label('Edit Catatan'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('User'),
                                TextEntry::make('created_at')
                                    ->label('Tanggal')
                                    ->dateTime('d F Y, H:i:s'),
                                TextEntry::make('status_label')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($record) => $record->status_badge),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('extraction_mode')
                                    ->label('Mode Ekstraksi')
                                    ->formatStateUsing(fn ($state) => $state === 'gemini' ? 'AI Gemini' : 'Manual Parser')
                                    ->badge()
                                    ->color(fn ($state) => $state === 'gemini' ? 'success' : 'info'),
                                TextEntry::make('use_mapping')
                                    ->label('Mapping ID')
                                    ->formatStateUsing(fn ($state) => $state ? 'Ya (Output Angka ID)' : 'Tidak (Output Teks)')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'primary' : 'gray'),
                            ]),
                    ])
                    ->columns(1),

                Section::make('Statistik File')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('total_files')
                                    ->label('Total File'),
                                TextEntry::make('successful_files')
                                    ->label('File Berhasil')
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('failed_files')
                                    ->label('File Gagal')
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                            ]),
                        TextEntry::make('file_names')
                            ->label('Daftar File')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->default('-'),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Section::make('Statistik Data')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('total_people')
                                    ->label('Total Orang')
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('imported_kk')
                                    ->label('KK Diimpor')
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                                    ->default(0),
                                TextEntry::make('imported_penduduk')
                                    ->label('Penduduk Diimpor')
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                                    ->default(0),
                                TextEntry::make('duplicates_found')
                                    ->label('Duplikat Ditemukan')
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                                    ->default(0),
                            ]),
                    ])
                    ->columns(1),

                Section::make('Detail Kualitas Data')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('summary_stats.ok_count')
                                    ->label('Data OK')
                                    ->badge()
                                    ->color('success')
                                    ->default('-'),
                                TextEntry::make('summary_stats.flagged_count')
                                    ->label('Perlu Pengecekan')
                                    ->badge()
                                    ->color('warning')
                                    ->default('-'),
                                TextEntry::make('summary_stats.ok_percentage')
                                    ->label('Persentase OK')
                                    ->default('-'),
                                TextEntry::make('summary_stats.nik_errors_count')
                                    ->label('Error NIK')
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                                    ->default(0),
                            ]),
                    ])
                    ->columns(1)
                    ->visible(fn ($record) => !empty($record->summary_stats))
                    ->collapsible(),

                Section::make('Error Details')
                    ->schema([
                        TextEntry::make('error_details')
                            ->label('Detail Error')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->default('Tidak ada error'),
                    ])
                    ->visible(fn ($record) => !empty($record->error_details))
                    ->collapsible()
                    ->collapsed(),

                Section::make('Catatan')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('')
                            ->default('Tidak ada catatan')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => !empty($record->notes))
                    ->collapsible(),
            ]);
    }
}
