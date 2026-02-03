<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeluargaResource\Pages;
use App\Filament\Resources\KeluargaResource\RelationManagers;
use App\Models\Keluarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KeluargaResource extends Resource
{
    protected static ?string $model = Keluarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Keluarga (KK)';

    protected static ?string $modelLabel = 'Kartu Keluarga';

    protected static ?string $pluralModelLabel = 'Kartu Keluarga';

    protected static ?string $navigationGroup = 'Kependudukan';

    protected static ?int $navigationSort = 2;

    // Allow create and edit for manual data management
    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        // Only SuperAdmin and Kades can delete
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasRole('kades'));
    }

    public static function canDeleteAny(): bool
    {
        // Only SuperAdmin and Kades can bulk delete
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasRole('kades'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dusun')
                    ->schema([
                        Forms\Components\Select::make('dusun_id')
                            ->label('Dusun')
                            ->relationship('dusun', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(function () {
                                $user = auth()->user();
                                if ($user && !$user->canAccessAllDusuns() && $user->dusun_id) {
                                    return $user->dusun_id;
                                }
                                return null;
                            })
                            ->disabled(fn () => auth()->user() && !auth()->user()->canAccessAllDusuns())
                            ->dehydrated(true)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Data Kartu Keluarga')
                    ->schema([
                        Forms\Components\TextInput::make('no_kk')
                            ->label('Nomor KK')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(16)
                            ->placeholder('Contoh: 1871021234567890'),
                        Forms\Components\TextInput::make('kepala_keluarga')
                            ->label('Nama Kepala Keluarga')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status_kk')
                            ->label('Status KK')
                            ->options([
                                'AKTIF' => 'Aktif',
                                'TIDAK AKTIF' => 'Tidak Aktif',
                            ])
                            ->default('AKTIF')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_terbit')
                            ->label('Tanggal Terbit'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Alamat')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('rt')
                            ->label('RT')
                            ->maxLength(3),
                        Forms\Components\TextInput::make('rw')
                            ->label('RW')
                            ->maxLength(3),
                        Forms\Components\TextInput::make('kelurahan_desa')
                            ->label('Kelurahan/Desa')
                            ->default('Kelurahan Gedongmeneng')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kecamatan')
                            ->label('Kecamatan')
                            ->default('Gedongmeneng')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kabupaten_kota')
                            ->label('Kabupaten/Kota')
                            ->default('Kota Bandar Lampung')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('provinsi')
                            ->label('Provinsi')
                            ->default('Lampung')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->maxLength(5),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Keterangan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->forAuthUser())
            ->columns([
                Tables\Columns\TextColumn::make('dusun.name')
                    ->label('Dusun')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->visible(fn () => auth()->user()?->canAccessAllDusuns() ?? false),
                Tables\Columns\TextColumn::make('no_kk')
                    ->label('No. KK')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kepala_keluarga')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(30)
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rt')
                    ->label('RT')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('rw')
                    ->label('RW')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('kelurahan_desa')
                    ->label('Kelurahan/Desa')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('penduduks_count')
                    ->label('Jumlah Anggota')
                    ->counts('penduduks')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status_kk')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'TIDAK AKTIF' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dusun_id')
                    ->label('Dusun')
                    ->relationship('dusun', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()?->canAccessAllDusuns() ?? false),
                    
                Tables\Filters\SelectFilter::make('status_kk')
                    ->label('Status KK')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'TIDAK AKTIF' => 'Tidak Aktif',
                    ])
                    ->default('AKTIF'),
                    
                Tables\Filters\SelectFilter::make('rt')
                    ->label('RT')
                    ->options(function () {
                        return \App\Models\Keluarga::query()
                            ->distinct()
                            ->whereNotNull('rt')
                            ->where('rt', '!=', '')
                            ->orderBy('rt')
                            ->pluck('rt', 'rt')
                            ->toArray();
                    })
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('rw')
                    ->label('RW')
                    ->options(function () {
                        return \App\Models\Keluarga::query()
                            ->distinct()
                            ->whereNotNull('rw')
                            ->where('rw', '!=', '')
                            ->orderBy('rw')
                            ->pluck('rw', 'rw')
                            ->toArray();
                    })
                    ->searchable(),
                    
                Tables\Filters\Filter::make('jumlah_anggota')
                    ->label('Jumlah Anggota')
                    ->form([
                        Forms\Components\TextInput::make('min_anggota')
                            ->label('Minimal')
                            ->numeric()
                            ->placeholder('0'),
                        Forms\Components\TextInput::make('max_anggota')
                            ->label('Maksimal')
                            ->numeric()
                            ->placeholder('10'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                isset($data['min_anggota']),
                                fn (Builder $query): Builder => $query->has('penduduks', '>=', $data['min_anggota']),
                            )
                            ->when(
                                isset($data['max_anggota']),
                                fn (Builder $query): Builder => $query->has('penduduks', '<=', $data['max_anggota']),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (isset($data['min_anggota'])) {
                            $indicators[] = 'Min anggota: ' . $data['min_anggota'];
                        }
                        if (isset($data['max_anggota'])) {
                            $indicators[] = 'Max anggota: ' . $data['max_anggota'];
                        }
                        return $indicators;
                    }),
                    
                Tables\Filters\Filter::make('tanggal_terbit')
                    ->form([
                        Forms\Components\DatePicker::make('terbit_dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('terbit_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['terbit_dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terbit', '>=', $date),
                            )
                            ->when(
                                $data['terbit_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terbit', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['terbit_dari'] ?? null) {
                            $indicators[] = 'Terbit dari: ' . \Carbon\Carbon::parse($data['terbit_dari'])->format('d/m/Y');
                        }
                        if ($data['terbit_sampai'] ?? null) {
                            $indicators[] = 'Terbit sampai: ' . \Carbon\Carbon::parse($data['terbit_sampai'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
                    
                Tables\Filters\Filter::make('keluarga_besar')
                    ->label('Keluarga Besar (5+ anggota)')
                    ->query(fn (Builder $query): Builder => $query->has('penduduks', '>=', 5))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                // Quick Action: Tambah Anggota
                Tables\Actions\Action::make('tambah_anggota')
                    ->label('Tambah Anggota')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->url(fn ($record) => PendudukResource::getUrl('create', ['keluarga_id' => $record->id]))
                    ->tooltip('Tambah anggota baru ke KK ini'),
                
                // Quick Action: Ganti Kepala Keluarga
                Tables\Actions\Action::make('ganti_kepala')
                    ->label('Ganti Kepala KK')
                    ->icon('heroicon-o-user-circle')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('new_kepala_id')
                            ->label('Pilih Kepala Keluarga Baru')
                            ->options(fn ($record) => $record->penduduks->pluck('nama_lengkap', 'id'))
                            ->required()
                            ->searchable()
                            ->helperText('Pilih dari anggota keluarga yang ada'),
                    ])
                    ->action(function ($record, array $data) {
                        $newKepala = \App\Models\Penduduk::find($data['new_kepala_id']);
                        if ($newKepala) {
                            $record->update([
                                'kepala_keluarga' => $newKepala->nama_lengkap
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Kepala Keluarga Diperbarui')
                                ->body("Kepala keluarga berhasil diubah menjadi {$newKepala->nama_lengkap}")
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Ganti Kepala Keluarga')
                    ->modalDescription('Pilih anggota keluarga yang akan menjadi kepala keluarga baru')
                    ->tooltip('Ganti kepala keluarga'),
                    
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole('kades'))),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole('kades'))),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        return response()->streamDownload(function () use ($query) {
                            echo \App\Helpers\ExportHelper::exportKeluargaToExcel($query->get());
                        }, 'keluarga-' . now()->format('Y-m-d-His') . '.csv');
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->filtersFormColumns(2)
            ->emptyStateHeading('Belum Ada Data Keluarga')
            ->emptyStateDescription('Data keluarga akan muncul setelah ekstraksi KK melalui panel "Ekstrak Kartu Keluarga"')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->forAuthUser();
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
            'index' => Pages\ListKeluargas::route('/'),
            'create' => Pages\CreateKeluarga::route('/create'),
            'edit' => Pages\EditKeluarga::route('/{record}/edit'),
        ];
    }
}
