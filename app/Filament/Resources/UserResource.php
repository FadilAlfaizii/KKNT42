<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Settings\MailSettings;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static int $globalSearchResultsLimit = 20;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->hiddenLabel()
                            ->avatar()
                            ->collection('avatars')
                            ->alignCenter()
                            ->columnSpanFull(),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->revealable()
                                    ->required()
                                    ->label('Kata Sandi'),
                                Forms\Components\TextInput::make('passwordConfirmation')
                                    ->password()
                                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->revealable()
                                    ->same('password')
                                    ->required()
                                    ->label('Konfirmasi Kata Sandi'),
                            ])
                            ->compact()
                            ->hidden(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('email_verified_at')
                                    ->label('Email Terverifikasi Pada')
                                    ->content(fn(User $record): ?string => new HtmlString("$record->email_verified_at")),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->content(fn(User $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->content(fn(User $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->compact()
                            ->hidden(fn(string $operation): bool => $operation === 'create'),
                    ])
                    ->columnSpan(1),

                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Detail')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('fullname')
                                    ->required()
                                    ->maxLength(255)
                                    ->live()
                                    ->label('Nama Lengkap')
                                    ->rules(function ($record) {
                                        $userId = $record?->id;
                                        return $userId
                                            ? ['unique:users,fullname,' . $userId]
                                            : ['unique:users,fullname'];
                                    }),

                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Email')
                                    ->rules(function ($record) {
                                        $userId = $record?->id;
                                        return $userId
                                            ? ['unique:users,email,' . $userId]
                                            : ['unique:users,email'];
                                    }),

                                Forms\Components\TextInput::make('phone_number')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nomor Telepon')
                                    ->dehydrateStateUsing(function (string $state): string {
                                        // Ensure the phone number starts with +62
                                        if (Str::startsWith($state, '08')) {
                                            $state = '628' . substr($state, 2);
                                        }
                                        return $state;
                                    })
                                    ->rules(['regex:/^(628|08)[0-9]{9,13}$/']),

                                Forms\Components\TextInput::make('bio')
                                    ->label('Biografi'),

                                Forms\Components\Select::make('dusun_id')
                                    ->label('Dusun')
                                    ->relationship('dusun', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->helperText('Pilih dusun untuk Kadus. Kosongkan untuk Kades/SuperAdmin.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Peran')
                            ->icon('fluentui-shield-task-48')
                            ->schema([
                                Select::make('roles')
                                    ->hiddenLabel()
                                    ->relationship('roles', 'name')
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->optionsLimit(5)
                                    ->columnSpanFull()
                                    ->label('Peran'),
                            ])
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Profil')
                    ->collection('avatars')
                    ->wrap(),
                Tables\Columns\TextColumn::make('fullname')->label('Nama Lengkap')
                    ->description('Nama lengkap pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')->label('Nomor Telepon')
                    ->description('Nomor telepon pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Peran')
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge(),
                Tables\Columns\TextColumn::make('email')->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->email;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'fullname', 'phone_number', 'bio'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->fullname,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akses';
    }
}
