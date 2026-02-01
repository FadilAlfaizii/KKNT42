<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendudukResource\Pages;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Penduduk';

    protected static ?string $modelLabel = 'Penduduk';

    protected static ?string $pluralModelLabel = 'Penduduk';

    protected static ?string $navigationGroup = 'Kependudukan';

    protected static ?int $navigationSort = 1;

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
                Forms\Components\Section::make('Informasi Keluarga')
                    ->schema([
                        Forms\Components\Select::make('keluarga_id')
                            ->label('Kartu Keluarga')
                            ->relationship('keluarga', 'no_kk', function ($query) {
                                $user = auth()->user();
                                if ($user && !$user->canAccessAllDusuns() && $user->dusun_id) {
                                    return $query->where('dusun_id', $user->dusun_id);
                                }
                                return $query;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $keluarga = \App\Models\Keluarga::find($state);
                                    if ($keluarga) {
                                        $set('dusun_id', $keluarga->dusun_id);
                                    }
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('dusun_id'),
                    ]),

                Forms\Components\Tabs::make('Data Penduduk')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Data Pribadi')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(16)
                                    ->placeholder('Contoh: 1871021234567890'),
                                Forms\Components\TextInput::make('nama_lengkap')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'LAKI-LAKI' => 'Laki-laki',
                                        'PEREMPUAN' => 'Perempuan',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('status_dalam_keluarga')
                                    ->label('Status dalam Keluarga')
                                    ->options([
                                        'KEPALA KELUARGA' => 'Kepala Keluarga',
                                        'SUAMI' => 'Suami',
                                        'ISTRI' => 'Istri',
                                        'ANAK' => 'Anak',
                                        'MENANTU' => 'Menantu',
                                        'CUCU' => 'Cucu',
                                        'ORANGTUA' => 'Orangtua',
                                        'MERTUA' => 'Mertua',
                                        'FAMILI LAIN' => 'Famili Lain',
                                        'PEMBANTU' => 'Pembantu',
                                        'LAINNYA' => 'Lainnya',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->reactive(),
                                Forms\Components\TextInput::make('nama_ayah')
                                    ->label('Nama Ayah')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nama_ibu')
                                    ->label('Nama Ibu')
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Data Tambahan')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Select::make('agama')
                                    ->label('Agama')
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                    ]),
                                Forms\Components\Select::make('pendidikan')
                                    ->label('Pendidikan')
                                    ->options([
                                        'TIDAK/BELUM SEKOLAH' => 'Tidak/Belum Sekolah',
                                        'BELUM TAMAT SD/SEDERAJAT' => 'Belum Tamat SD/Sederajat',
                                        'TAMAT SD/SEDERAJAT' => 'Tamat SD/Sederajat',
                                        'SLTP/SEDERAJAT' => 'SLTP/Sederajat',
                                        'SLTA/SEDERAJAT' => 'SLTA/Sederajat',
                                        'DIPLOMA I/II' => 'Diploma I/II',
                                        'AKADEMI/DIPLOMA III/S. MUDA' => 'Akademi/Diploma III/S. Muda',
                                        'DIPLOMA IV/STRATA I' => 'Diploma IV/Strata I',
                                        'STRATA II' => 'Strata II',
                                        'STRATA III' => 'Strata III',
                                    ]),
                                Forms\Components\TextInput::make('pekerjaan')
                                    ->label('Pekerjaan')
                                    ->maxLength(255)
                                    ->placeholder('Contoh: BELUM/TIDAK BEKERJA'),
                                Forms\Components\Select::make('status_perkawinan')
                                    ->label('Status Perkawinan')
                                    ->options([
                                        'BELUM KAWIN' => 'Belum Kawin',
                                        'KAWIN' => 'Kawin',
                                        'CERAI HIDUP' => 'Cerai Hidup',
                                        'CERAI MATI' => 'Cerai Mati',
                                    ]),
                                Forms\Components\Select::make('golongan_darah')
                                    ->label('Golongan Darah')
                                    ->options([
                                        'A' => 'A',
                                        'B' => 'B',
                                        'AB' => 'AB',
                                        'O' => 'O',
                                        'TIDAK TAHU' => 'Tidak Tahu',
                                    ]),
                                Forms\Components\TextInput::make('kewarganegaraan')
                                    ->label('Kewarganegaraan')
                                    ->default('WNI')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('no_paspor')
                                    ->label('No. Paspor')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('no_akta_lahir')
                                    ->label('No. Akta Lahir')
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Status')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Select::make('status_penduduk')
                                    ->label('Status Penduduk')
                                    ->options([
                                        'TETAP' => 'Tetap',
                                        'TIDAK TETAP' => 'Tidak Tetap',
                                        'PENDATANG' => 'Pendatang',
                                        'MENINGGAL' => 'Meninggal',
                                        'PINDAH' => 'Pindah',
                                    ])
                                    ->default('TETAP')
                                    ->required(),
                                Forms\Components\Textarea::make('keterangan')
                                    ->label('Keterangan')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('keluarga.no_kk')
                    ->label('No. KK')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Penduduk $record): string => $record->tempat_lahir 
                        ? "{$record->tempat_lahir}, " . ($record->tanggal_lahir ? \Carbon\Carbon::parse($record->tanggal_lahir)->format('d/m/Y') : '-')
                        : ''),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LAKI-LAKI' => 'info',
                        'PEREMPUAN' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => $state === 'LAKI-LAKI' ? 'L' : 'P'),
                Tables\Columns\TextColumn::make('umur')
                    ->label('Umur')
                    ->suffix(' th')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agama')
                    ->label('Agama')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pendidikan')
                    ->label('Pendidikan')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_dalam_keluarga')
                    ->label('Status')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_penduduk')
                    ->label('Status Penduduk')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'TETAP' => 'success',
                        'TIDAK TETAP' => 'warning',
                        'PENDATANG' => 'info',
                        'MENINGGAL' => 'danger',
                        'PINDAH' => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),
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
                    
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'LAKI-LAKI' => 'Laki-laki',
                        'PEREMPUAN' => 'Perempuan',
                    ]),
                    
                Tables\Filters\SelectFilter::make('agama')
                    ->label('Agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                    ])
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->label('Pendidikan')
                    ->options([
                        'Tidak/Belum Sekolah' => 'Tidak/Belum Sekolah',
                        'Belum Tamat SD/Sederajat' => 'Belum Tamat SD/Sederajat',
                        'Tamat SD/Sederajat' => 'Tamat SD/Sederajat',
                        'SLTP/Sederajat' => 'SLTP/Sederajat',
                        'SLTA/Sederajat' => 'SLTA/Sederajat',
                        'Diploma I/II' => 'Diploma I/II',
                        'Akademi/Diploma III/S. Muda' => 'Akademi/Diploma III/S. Muda',
                        'Diploma IV/Strata I' => 'Diploma IV/Strata I',
                        'Strata II' => 'Strata II',
                        'Strata III' => 'Strata III',
                    ])
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->options(function () {
                        return \App\Models\Penduduk::query()
                            ->distinct()
                            ->whereNotNull('pekerjaan')
                            ->where('pekerjaan', '!=', '')
                            ->pluck('pekerjaan', 'pekerjaan')
                            ->take(50)
                            ->toArray();
                    })
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->options([
                        'BELUM KAWIN' => 'Belum Kawin',
                        'KAWIN' => 'Kawin',
                        'CERAI HIDUP' => 'Cerai Hidup',
                        'CERAI MATI' => 'Cerai Mati',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status_dalam_keluarga')
                    ->label('Status Dalam Keluarga')
                    ->options([
                        'KEPALA KELUARGA' => 'Kepala Keluarga',
                        'SUAMI' => 'Suami',
                        'ISTRI' => 'Istri',
                        'ANAK' => 'Anak',
                        'MENANTU' => 'Menantu',
                        'CUCU' => 'Cucu',
                        'ORANGTUA' => 'Orangtua',
                        'MERTUA' => 'Mertua',
                        'FAMILI LAIN' => 'Famili Lain',
                        'PEMBANTU' => 'Pembantu',
                        'LAINNYA' => 'Lainnya',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status_penduduk')
                    ->label('Status Penduduk')
                    ->options([
                        'TETAP' => 'Tetap',
                        'TIDAK TETAP' => 'Tidak Tetap',
                        'PENDATANG' => 'Pendatang',
                        'MENINGGAL' => 'Meninggal',
                        'PINDAH' => 'Pindah',
                    ])
                    ->default('TETAP'),
                    
                Tables\Filters\Filter::make('umur')
                    ->form([
                        Forms\Components\TextInput::make('umur_dari')
                            ->label('Umur Dari')
                            ->numeric()
                            ->placeholder('0'),
                        Forms\Components\TextInput::make('umur_sampai')
                            ->label('Umur Sampai')
                            ->numeric()
                            ->placeholder('100'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['umur_dari'],
                                fn (Builder $query, $umur): Builder => $query->where('umur', '>=', $umur),
                            )
                            ->when(
                                $data['umur_sampai'],
                                fn (Builder $query, $umur): Builder => $query->where('umur', '<=', $umur),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['umur_dari'] ?? null) {
                            $indicators[] = 'Umur dari: ' . $data['umur_dari'];
                        }
                        if ($data['umur_sampai'] ?? null) {
                            $indicators[] = 'Umur sampai: ' . $data['umur_sampai'];
                        }
                        return $indicators;
                    }),
                    
                Tables\Filters\Filter::make('dewasa')
                    ->label('Dewasa (17+)')
                    ->query(fn (Builder $query): Builder => $query->where('umur', '>=', 17))
                    ->toggle(),
                    
                Tables\Filters\Filter::make('kepala_keluarga')
                    ->label('Kepala Keluarga')
                    ->query(fn (Builder $query): Builder => $query->where('status_dalam_keluarga', 'KEPALA KELUARGA'))
                    ->toggle(),
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
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        return response()->streamDownload(function () use ($query) {
                            echo \App\Helpers\ExportHelper::exportPendudukToExcel($query->get());
                        }, 'penduduk-' . now()->format('Y-m-d-His') . '.csv');
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->filtersFormColumns(3)
            ->emptyStateHeading('Belum Ada Data Penduduk')
            ->emptyStateDescription('Data penduduk akan muncul setelah ekstraksi KK melalui panel "Ekstrak Kartu Keluarga"')
            ->emptyStateIcon('heroicon-o-user-group');
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
            'index' => Pages\ListPenduduks::route('/'),
        ];
    }
}
