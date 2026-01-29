<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapPointResource\Pages;
use App\Models\MapPoint;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;

class MapPointResource extends Resource
{
    protected static ?string $model = MapPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Peta Interaktif';

    protected static ?string $modelLabel = 'Titik Lokasi';

    protected static ?string $pluralModelLabel = 'Titik Lokasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Lokasi')
                    ->schema([
                        Forms\Components\Select::make('dusun_id')
                            ->label('Dusun')
                            ->relationship('dusun', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(function () {
                                $user = auth()->user();
                                // If kadus, auto-assign to their dusun
                                if ($user && !$user->canAccessAllDusuns() && $user->dusun_id) {
                                    return $user->dusun_id;
                                }
                                return null;
                            })
                            ->disabled(fn () => auth()->user() && !auth()->user()->canAccessAllDusuns())
                            ->dehydrated(true)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'UMKM' => 'UMKM',
                                'Pendidikan' => 'Pendidikan',
                                'Olahraga' => 'Olahraga',
                                'Ibadah' => 'Ibadah',
                                'Kesehatan' => 'Kesehatan',
                                'Pemerintah' => 'Pemerintah',
                            ])
                            ->searchable(),
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Koordinat')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('latitude')
                                    ->numeric()
                                    ->required()
                                    ->readonly()
                                    ->default(-5.2962)
                                    ->minValue(-90)
                                    ->maxValue(90)
                                    ->step('0.00000001')
                                    ->label('Latitude'),
                                TextInput::make('longitude')
                                    ->numeric()
                                    ->required()
                                    ->readonly()
                                    ->default(105.4478)
                                    ->minValue(-180)
                                    ->maxValue(180)
                                    ->step('0.00000001')
                                    ->label('Longitude'),
                            ])
                            ->columns(2),
                        Map::make('coordinates')
                            ->label('Peta Lokasi')
                            ->liveLocation(true)
                            ->afterStateUpdated(function (Set $set, ?array $state): void {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -5.2962, longitude: 105.4478)
                            ->clickable(true)
                            ->showMarker()
                            ->showZoomControl()
                            ->draggable(),
                    ]),
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->forAuthUser())
            ->columns([
                Tables\Columns\TextColumn::make('dusun.name')
                    ->label('Dusun')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->visible(fn () => auth()->user()?->canAccessAllDusuns() ?? false),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
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
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'UMKM' => 'UMKM',
                        'Pendidikan' => 'Pendidikan',
                        'Olahraga' => 'Olahraga',
                        'Ibadah' => 'Ibadah',
                        'Kesehatan' => 'Kesehatan',
                        'Pemerintah' => 'Pemerintah',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Massal'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListMapPoints::route('/'),
            'create' => Pages\CreateMapPoint::route('/create'),
            'edit' => Pages\EditMapPoint::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Peta';
    }
}
