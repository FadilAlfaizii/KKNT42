<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KKExtractionHistoryResource\Pages;
use App\Filament\Resources\KKExtractionHistoryResource\RelationManagers;
use App\Models\KKExtractionHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KKExtractionHistoryResource extends Resource
{
    protected static ?string $model = KKExtractionHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Kependudukan';
    protected static ?string $navigationLabel = 'Riwayat Ekstraksi KK';
    protected static ?string $modelLabel = 'Riwayat Ekstraksi';
    protected static ?string $pluralModelLabel = 'Riwayat Ekstraksi';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('extraction_mode')
                    ->label('Mode')
                    ->formatStateUsing(fn ($state) => $state === 'gemini' ? 'AI Gemini' : 'Manual')
                    ->colors([
                        'success' => 'gemini',
                        'info' => 'manual',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->colors([
                        'success' => fn ($record) => $record->status === 'completed' || $record->status === 'imported',
                        'warning' => fn ($record) => $record->status === 'partial',
                        'danger' => fn ($record) => $record->status === 'failed',
                    ]),
                
                TextColumn::make('total_people')
                    ->label('Total Orang')
                    ->alignCenter()
                    ->sortable(),
                
                TextColumn::make('imported_kk')
                    ->label('KK Import')
                    ->alignCenter()
                    ->sortable()
                    ->default(0),
                
                TextColumn::make('imported_penduduk')
                    ->label('Penduduk Import')
                    ->alignCenter()
                    ->sortable()
                    ->default(0),
                
                TextColumn::make('duplicates_found')
                    ->label('Duplikat')
                    ->alignCenter()
                    ->sortable()
                    ->default(0)
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success'),
                
                TextColumn::make('successful_files')
                    ->label('File Sukses')
                    ->alignCenter()
                    ->sortable(),
                
                TextColumn::make('failed_files')
                    ->label('File Gagal')
                    ->alignCenter()
                    ->sortable()
                    ->default(0)
                    ->color(fn ($state) => $state > 0 ? 'danger' : null),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('extraction_mode')
                    ->label('Mode Ekstraksi')
                    ->options([
                        'manual' => 'Manual',
                        'gemini' => 'AI Gemini',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'completed' => 'Selesai',
                        'imported' => 'Diimpor',
                        'partial' => 'Sebagian',
                        'failed' => 'Gagal',
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Edit Catatan'),
                Tables\Actions\Action::make('download')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn ($record) => !empty($record->excel_file_path) && file_exists($record->excel_file_path))
                    ->action(function ($record) {
                        return response()->download($record->excel_file_path);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKKExtractionHistories::route('/'),
            'view' => Pages\ViewKKExtractionHistory::route('/{record}'),
            'edit' => Pages\EditKKExtractionHistory::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable create - history auto-generated
    }
}
