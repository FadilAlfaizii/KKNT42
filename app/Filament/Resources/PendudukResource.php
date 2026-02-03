<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendudukResource\Pages;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Kependudukan';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Keluarga')
                    ->schema([
                        Forms\Components\Select::make('keluarga_id')
                            ->label('Kartu Keluarga')
                            ->relationship('keluarga', 'no_kk')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $keluarga = \App\Models\Keluarga::find($state);
                                    if ($keluarga) {
                                        $set('alamat', $keluarga->alamat);
                                        $set('rt', $keluarga->rt);
                                        $set('rw', $keluarga->rw);
                                        $set('dusun_id', $keluarga->dusun_id);
                                    }
                                }
                            }),
                        Forms\Components\Select::make('dusun_id')
                            ->label('Dusun')
                            ->relationship('dusun', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->length(16)
                            ->numeric()
                            ->rules(['digits:16'])
                            ->validationMessages([
                                'digits' => 'NIK harus tepat 16 digit',
                                'unique' => 'NIK sudah terdaftar di sistem',
                                'numeric' => 'NIK hanya boleh berisi angka',
                            ])
                            ->helperText('NIK harus 16 digit angka')
                            ->placeholder('3201234567890001')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (strlen($state) === 16 && is_numeric($state)) {
                                    // Extract jenis kelamin (tanggal > 40 = perempuan)
                                    $tglLahir = substr($state, 6, 6);
                                    $tgl = (int)substr($tglLahir, 0, 2);
                                    if ($tgl > 40 && !$get('jenis_kelamin')) {
                                        $set('jenis_kelamin', 'PEREMPUAN');
                                    } elseif ($tgl <= 31 && !$get('jenis_kelamin')) {
                                        $set('jenis_kelamin', 'LAKI-LAKI');
                                    }
                                }
                            }),
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
                            ->label('Status Dalam Keluarga')
                            ->options([
                                'KEPALA KELUARGA' => 'Kepala Keluarga',
                                'SUAMI' => 'Suami',
                                'ISTRI' => 'Istri',
                                'ANAK' => 'Anak',
                                'MENANTU' => 'Menantu',
                                'CUCU' => 'Cucu',
                                'ORANGTUA' => 'Orang Tua',
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
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    try {
                                        $umur = \Carbon\Carbon::parse($state)->age;
                                        $set('umur_manual', $umur);
                                    } catch (\Exception $e) {
                                        // Ignore
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('umur_manual')
                            ->label('Umur (tahun)')
                            ->numeric()
                            ->suffix('tahun')
                            ->helperText('Otomatis terhitung dari tanggal lahir, bisa diedit manual'),
                        Forms\Components\Select::make('agama')
                            ->label('Agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katholik' => 'Katholik',
                                'Hindu' => 'Hindu',
                                'Budha' => 'Budha',
                                'Khonghucu' => 'Khonghucu',
                                'Kepercayaan Terhadap Tuhan YME / Lainnya' => 'Kepercayaan Terhadap Tuhan YME / Lainnya',
                            ]),
                        Forms\Components\Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+' => 'O+',
                                'O-' => 'O-',
                                'Tidak Tahu' => 'Tidak Tahu',
                            ])
                            ->searchable(),
                        Forms\Components\Select::make('pendidikan')
                            ->label('Pendidikan')
                            ->options([
                                'Tidak/Belum Sekolah' => 'Tidak/Belum Sekolah',
                                'Belum Tamat SD/Sederajat' => 'Belum Tamat SD/Sederajat',
                                'Tamat SD/Sederajat' => 'Tamat SD/Sederajat',
                                'SLTP/Sederajat' => 'SLTP/Sederajat',
                                'SLTA/Sederajat' => 'SLTA/Sederajat',
                                'Diploma I/II' => 'Diploma I/II',
                                'Diploma III' => 'Diploma III',
                                'Diploma IV/Strata I' => 'Diploma IV/Strata I',
                                'Strata II' => 'Strata II',
                                'Strata III' => 'Strata III',
                            ]),
                        Forms\Components\Select::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->options([
                                'Belum/Tidak Bekerja' => 'Belum/Tidak Bekerja',
                                'Mengurus Rumah Tangga' => 'Mengurus Rumah Tangga',
                                'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa',
                                'Pensiunan' => 'Pensiunan',
                                'Pegawai Negeri Sipil (PNS)' => 'Pegawai Negeri Sipil (PNS)',
                                'Tentara Nasional Indonesia (TNI)' => 'Tentara Nasional Indonesia (TNI)',
                                'Kepolisian RI (POLRI)' => 'Kepolisian RI (POLRI)',
                                'Perdagangan' => 'Perdagangan',
                                'Petani/Pekebun' => 'Petani/Pekebun',
                                'Peternak' => 'Peternak',
                                'Nelayan/Perikanan' => 'Nelayan/Perikanan',
                                'Industri' => 'Industri',
                                'Konstruksi' => 'Konstruksi',
                                'Transportasi' => 'Transportasi',
                                'Karyawan Swasta' => 'Karyawan Swasta',
                                'Karyawan BUMN' => 'Karyawan BUMN',
                                'Karyawan BUMD' => 'Karyawan BUMD',
                                'Karyawan Honorer' => 'Karyawan Honorer',
                                'Buruh Harian Lepas' => 'Buruh Harian Lepas',
                                'Buruh Tani/Perkebunan' => 'Buruh Tani/Perkebunan',
                                'Buruh Nelayan/Perikanan' => 'Buruh Nelayan/Perikanan',
                                'Buruh Peternakan' => 'Buruh Peternakan',
                                'Pembantu Rumah Tangga' => 'Pembantu Rumah Tangga',
                                'Tukang Cukur' => 'Tukang Cukur',
                                'Tukang Listrik' => 'Tukang Listrik',
                                'Tukang Batu' => 'Tukang Batu',
                                'Tukang Kayu' => 'Tukang Kayu',
                                'Tukang Sol Sepatu' => 'Tukang Sol Sepatu',
                                'Tukang Las/Pandai Besi' => 'Tukang Las/Pandai Besi',
                                'Tukang Jahit' => 'Tukang Jahit',
                                'Tukang Gigi' => 'Tukang Gigi',
                                'Penata Rias' => 'Penata Rias',
                                'Penata Busana' => 'Penata Busana',
                                'Penata Rambut' => 'Penata Rambut',
                                'Mekanik' => 'Mekanik',
                                'Seniman' => 'Seniman',
                                'Tabib' => 'Tabib',
                                'Paraji' => 'Paraji',
                                'Perancang Busana' => 'Perancang Busana',
                                'Penterjemah' => 'Penterjemah',
                                'Imam Masjid' => 'Imam Masjid',
                                'Pendeta' => 'Pendeta',
                                'Pastor' => 'Pastor',
                                'Wartawan' => 'Wartawan',
                                'Ustadz/Mubaligh' => 'Ustadz/Mubaligh',
                                'Juru Masak' => 'Juru Masak',
                                'Promotor Acara' => 'Promotor Acara',
                                'Dosen' => 'Dosen',
                                'Guru' => 'Guru',
                                'Pilot' => 'Pilot',
                                'Pengacara' => 'Pengacara',
                                'Notaris' => 'Notaris',
                                'Arsitek' => 'Arsitek',
                                'Akuntan' => 'Akuntan',
                                'Konsultan' => 'Konsultan',
                                'Dokter' => 'Dokter',
                                'Bidan' => 'Bidan',
                                'Perawat' => 'Perawat',
                                'Apoteker' => 'Apoteker',
                                'Psikiater/Psikolog' => 'Psikiater/Psikolog',
                                'Penyiar Televisi' => 'Penyiar Televisi',
                                'Penyiar Radio' => 'Penyiar Radio',
                                'Pelaut' => 'Pelaut',
                                'Peneliti' => 'Peneliti',
                                'Sopir' => 'Sopir',
                                'Pialang' => 'Pialang',
                                'Paranormal' => 'Paranormal',
                                'Pedagang' => 'Pedagang',
                                'Perangkat Desa' => 'Perangkat Desa',
                                'Kepala Desa' => 'Kepala Desa',
                                'Wiraswasta' => 'Wiraswasta',
                            ])
                            ->searchable(),
                        Forms\Components\Select::make('status_perkawinan')
                            ->label('Status Perkawinan')
                            ->options([
                                'BELUM KAWIN' => 'Belum Kawin',
                                'KAWIN' => 'Kawin',
                                'CERAI HIDUP' => 'Cerai Hidup',
                                'CERAI MATI' => 'Cerai Mati',
                            ]),
                        Forms\Components\TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->maxLength(255),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Alamat & Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('alamat')
                            ->label('Alamat')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rt')
                            ->label('RT')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('rw')
                            ->label('RW')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('alamat_sekarang')
                            ->label('Alamat Sekarang')
                            ->maxLength(255),
                    ])->columns(2)->collapsed(),
                    
                Forms\Components\Section::make('Status & Keterangan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(1)->collapsed(),
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
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'LAKI-LAKI' => 'L',
                        'PEREMPUAN' => 'P',
                        default => $state ?? '-'
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'LAKI-LAKI' => 'info',
                        'PEREMPUAN' => 'danger',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('umur')
                    ->label('Umur')
                    ->state(fn (Penduduk $record): string => $record->umur ? (string) $record->umur : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_dalam_keluarga')
                    ->label('Status KK')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'KEPALA KELUARGA' => 'success',
                        'SUAMI', 'ISTRI' => 'info',
                        'ANAK' => 'warning',
                        default => 'gray'
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('agama')
                    ->label('Agama')
                    ->badge()
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pendidikan')
                    ->label('Pendidikan')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->badge()
                    ->color('warning')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->label('Gol. Darah')
                    ->badge()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
                // Hidden searchable columns for advanced search
                Tables\Columns\TextColumn::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_penduduk')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'TETAP' => 'success',
                        'TIDAK TETAP' => 'warning',
                        'PENDATANG' => 'info',
                        'MENINGGAL' => 'danger',
                        'PINDAH' => 'gray',
                        default => 'info'
                    })
                    ->default('HIDUP')
                    ->sortable()
                    ->toggleable(),
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
                        'Katholik' => 'Katholik',
                        'Hindu' => 'Hindu',
                        'Budha' => 'Budha',
                        'Khonghucu' => 'Khonghucu',
                        'Kepercayaan Terhadap Tuhan YME / Lainnya' => 'Lainnya',
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
                        'ORANGTUA' => 'Orang Tua',
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
                    ]),
                    
                // Advanced Filters
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->label('Pendidikan')
                    ->options([
                        'Tidak/Belum Sekolah' => 'Tidak/Belum Sekolah',
                        'Belum Tamat SD/Sederajat' => 'Belum Tamat SD/Sederajat',
                        'Tamat SD/Sederajat' => 'Tamat SD/Sederajat',
                        'SLTP/Sederajat' => 'SLTP/Sederajat',
                        'SLTA/Sederajat' => 'SLTA/Sederajat',
                        'Diploma I/II' => 'Diploma I/II',
                        'Diploma III' => 'Diploma III',
                        'Diploma IV/Strata I' => 'Diploma IV/Strata I',
                        'Strata II' => 'Strata II',
                        'Strata III' => 'Strata III',
                    ])
                    ->multiple()
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->options([
                        'Belum/Tidak Bekerja' => 'Belum/Tidak Bekerja',
                        'Mengurus Rumah Tangga' => 'Mengurus Rumah Tangga',
                        'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa',
                        'Pensiunan' => 'Pensiunan',
                        'Pegawai Negeri Sipil (PNS)' => 'Pegawai Negeri Sipil (PNS)',
                        'Petani/Pekebun' => 'Petani/Pekebun',
                        'Karyawan Swasta' => 'Karyawan Swasta',
                        'Buruh Harian Lepas' => 'Buruh Harian Lepas',
                        'Dosen' => 'Dosen',
                        'Guru' => 'Guru',
                        'Pedagang' => 'Pedagang',
                        'Wiraswasta' => 'Wiraswasta',
                    ])
                    ->multiple()
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->options([
                        'BELUM KAWIN' => 'Belum Kawin',
                        'KAWIN' => 'Kawin',
                        'CERAI HIDUP' => 'Cerai Hidup',
                        'CERAI MATI' => 'Cerai Mati',
                    ])
                    ->multiple(),
                    
                Tables\Filters\Filter::make('umur_range')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('umur_dari')
                                    ->label('Umur Minimal')
                                    ->numeric()
                                    ->placeholder('0')
                                    ->suffix('tahun'),
                                Forms\Components\TextInput::make('umur_sampai')
                                    ->label('Umur Maksimal')
                                    ->numeric()
                                    ->placeholder('120')
                                    ->suffix('tahun'),
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['umur_dari'],
                                fn ($query, $umur) => $query->whereRaw('(
                                    CASE 
                                        WHEN umur_manual IS NOT NULL THEN umur_manual
                                        WHEN tanggal_lahir IS NOT NULL THEN (CAST(strftime(\'%Y\', \'now\') AS INTEGER) - CAST(strftime(\'%Y\', tanggal_lahir) AS INTEGER))
                                        ELSE 0
                                    END
                                ) >= ?', [$umur])
                            )
                            ->when(
                                $data['umur_sampai'],
                                fn ($query, $umur) => $query->whereRaw('(
                                    CASE 
                                        WHEN umur_manual IS NOT NULL THEN umur_manual
                                        WHEN tanggal_lahir IS NOT NULL THEN (CAST(strftime(\'%Y\', \'now\') AS INTEGER) - CAST(strftime(\'%Y\', tanggal_lahir) AS INTEGER))
                                        ELSE 0
                                    END
                                ) <= ?', [$umur])
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['umur_dari'] && !$data['umur_sampai']) {
                            return null;
                        }
                        
                        if ($data['umur_dari'] && $data['umur_sampai']) {
                            return 'Umur: ' . $data['umur_dari'] . '-' . $data['umur_sampai'] . ' tahun';
                        }
                        
                        if ($data['umur_dari']) {
                            return 'Umur minimal: ' . $data['umur_dari'] . ' tahun';
                        }
                        
                        return 'Umur maksimal: ' . $data['umur_sampai'] . ' tahun';
                    }),
                    
                // Quick Filter Presets
                Tables\Filters\Filter::make('usia_produktif')
                    ->label('Usia Produktif (15-64 th)')
                    ->toggle()
                    ->query(fn ($query) => $query->whereRaw('(
                        CASE 
                            WHEN umur_manual IS NOT NULL THEN umur_manual
                            WHEN tanggal_lahir IS NOT NULL THEN (CAST(strftime(\'%Y\', \'now\') AS INTEGER) - CAST(strftime(\'%Y\', tanggal_lahir) AS INTEGER))
                            ELSE 0
                        END
                    ) BETWEEN 15 AND 64')),
                    
                Tables\Filters\Filter::make('anak_anak')
                    ->label('Anak-anak (< 15 th)')
                    ->toggle()
                    ->query(fn ($query) => $query->whereRaw('(
                        CASE 
                            WHEN umur_manual IS NOT NULL THEN umur_manual
                            WHEN tanggal_lahir IS NOT NULL THEN (CAST(strftime(\'%Y\', \'now\') AS INTEGER) - CAST(strftime(\'%Y\', tanggal_lahir) AS INTEGER))
                            ELSE 0
                        END
                    ) < 15')),
                    
                Tables\Filters\Filter::make('lansia')
                    ->label('Lansia (> 60 th)')
                    ->toggle()
                    ->query(fn ($query) => $query->whereRaw('(
                        CASE 
                            WHEN umur_manual IS NOT NULL THEN umur_manual
                            WHEN tanggal_lahir IS NOT NULL THEN (CAST(strftime(\'%Y\', \'now\') AS INTEGER) - CAST(strftime(\'%Y\', tanggal_lahir) AS INTEGER))
                            ELSE 0
                        END
                    ) > 60')),
                    
                Tables\Filters\Filter::make('kepala_keluarga')
                    ->label('Hanya Kepala Keluarga')
                    ->toggle()
                    ->query(fn ($query) => $query->where('status_dalam_keluarga', 'KEPALA KELUARGA')),
                    
                Tables\Filters\Filter::make('nik_kosong')
                    ->label('NIK Belum Lengkap')
                    ->toggle()
                    ->query(fn ($query) => $query->where(function ($q) {
                        $q->whereNull('nik')
                          ->orWhere('nik', '')
                          ->orWhereRaw('length(nik) != 16');
                    }))
                    ->default(false),            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->color('warning'),
                Tables\Actions\Action::make('pindah_kk')
                    ->label('Pindah KK')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('keluarga_id_baru')
                            ->label('KK Tujuan')
                            ->options(function () {
                                return \App\Models\Keluarga::query()
                                    ->forAuthUser()
                                    ->pluck('no_kk', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->helperText('Pilih KK tujuan untuk memindahkan penduduk'),
                        Forms\Components\Textarea::make('keterangan_pindah')
                            ->label('Keterangan')
                            ->placeholder('Alasan pindah KK...')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $kkBaru = \App\Models\Keluarga::find($data['keluarga_id_baru']);
                        if ($kkBaru) {
                            $record->update([
                                'keluarga_id' => $kkBaru->id,
                                'dusun_id' => $kkBaru->dusun_id,
                                'keterangan' => ($record->keterangan ? $record->keterangan . "\n" : '') .
                                    'Pindah dari KK ' . $record->keluarga->no_kk . ' ke KK ' . $kkBaru->no_kk .
                                    ($data['keterangan_pindah'] ? ' (' . $data['keterangan_pindah'] . ')' : '') .
                                    ' pada ' . now()->format('d/m/Y')
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Berhasil Pindah KK')
                                ->body("{$record->nama} berhasil dipindahkan ke KK {$kkBaru->no_kk}")
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Pindah Kartu Keluarga')
                    ->modalDescription(fn ($record) => "Pindahkan {$record->nama} dari KK saat ini ke KK lain")
                    ->tooltip('Pindahkan anggota ke KK lain'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPenduduks::route('/'),
            'create' => Pages\CreatePenduduk::route('/create'),
            'edit' => Pages\EditPenduduk::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::forAuthUser()->count();
    }
}
