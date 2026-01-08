<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahResource\Pages;
use App\Models\Sekolah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;

class SekolahResource extends Resource
{
    protected static ?string $model = Sekolah::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Sekolah';
    protected static ?string $pluralModelLabel = 'Sekolah';
    protected static ?string $modelLabel = 'Sekolah';
    protected static int $globalSearchResultsLimit = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('image_url')
                            ->image()
                            ->directory('sekolah')
                            ->label('Gambar')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Sekolah'),

                        Forms\Components\TextInput::make('place_id')
                            ->maxLength(255)
                            ->label('ID Tempat')
                            ->helperText('Opsional - ID referensi dari Google Maps atau sistem lain'),

                        Forms\Components\Textarea::make('location')
                            ->required()
                            ->columnSpanFull()
                            ->label('Alamat'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Deskripsi'),

                        Grid::make()
                            ->schema([
                                TextInput::make('latitude')
                                    ->numeric()
                                    ->required()
                                    ->readonly()
                                    ->default(-5.3971)
                                    ->minValue(-90)
                                    ->maxValue(90)
                                    ->step('0.00000001')
                                    ->label('Latitude'),

                                TextInput::make('longitude')
                                    ->numeric()
                                    ->required()
                                    ->readonly()
                                    ->default(105.2668)
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
                                $set('latitude',  $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            ->columnSpanFull()
                            ->defaultLocation(latitude: -5.3971, longitude: 105.2668)
                            ->clickable(true)
                            ->showMarker()
                            ->showZoomControl()
                            ->draggable()
                            ->tilesUrl('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->square()
                    ->label('Gambar'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Sekolah'),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->wrap()
                    ->limit(50)
                    ->label('Alamat'),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric(8)
                    ->label('Latitude')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric(8)
                    ->label('Longitude')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('createdAt')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->label('Dibuat Pada'),
            ])
            ->defaultSort('createdAt', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Massal'),
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
            'index' => Pages\ListSekolahs::route('/'),
            'create' => Pages\CreateSekolah::route('/create'),
            'edit' => Pages\EditSekolah::route('/{record}/edit'),
        ];
    }
}
