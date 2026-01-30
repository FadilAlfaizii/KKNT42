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

    // Read-only resource - data comes from Ekstrak Kartu Keluarga
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
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
                    ]),
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
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole('kades'))),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole('kades'))),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Data Keluarga')
            ->emptyStateDescription('Data keluarga akan muncul setelah ekstraksi KK melalui panel "Ekstrak Kartu Keluarga"')
            ->emptyStateIcon('heroicon-o-document-text');
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
        ];
    }
}
