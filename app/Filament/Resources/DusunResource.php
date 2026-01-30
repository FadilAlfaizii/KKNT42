<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DusunResource\Pages;
use App\Filament\Resources\DusunResource\RelationManagers;
use App\Models\Dusun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DusunResource extends Resource
{
    protected static ?string $model = Dusun::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Data Dusun';

    protected static ?string $modelLabel = 'Dusun';

    protected static ?string $pluralModelLabel = 'Dusun';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dusun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Dusun')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('contoh: Dusun 1'),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('contoh: DSN1'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Dusun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Jumlah User')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('mapPoints_count')
                    ->label('Titik Lokasi')
                    ->counts('mapPoints')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('code', 'asc');
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
            'index' => Pages\ListDusuns::route('/'),
            'create' => Pages\CreateDusun::route('/create'),
            'edit' => Pages\EditDusun::route('/{record}/edit'),
        ];
    }
}
