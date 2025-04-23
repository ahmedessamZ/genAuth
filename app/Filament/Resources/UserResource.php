<?php

namespace App\Filament\Resources;

use App\Enums\UserStatusEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->label('Password')
                        ->maxLength(20)
                        ->minLength(6)
                        ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                        ->revealable()
                        ->autocomplete(false)
                        ->dehydrated(fn($state) => filled($state)),
                    Forms\Components\TextInput::make('country_code')
                        ->label('Country Code')
                        ->maxLength(5)
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone')
                        ->prefix(fn (?User $record) => $record ? $record->c_code : null)
                        ->maxLength(15)
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('status')
                        ->options([
                            UserStatusEnum::ACTIVE->value => 'Active',
                            UserStatusEnum::INACTIVE->value => 'Inactive',
                            UserStatusEnum::PENDING->value => 'Pending',
                        ]),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection('user-avatar')
                        ->columnSpan('full'),
                ])->columns(2)->columnSpan(2),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('user-avatar'),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable(),
                Tables\Columns\BooleanColumn::make('status'),
                Tables\Columns\TextColumn::make('country_code'),
                Tables\Columns\TextColumn::make('phone'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        UserStatusEnum::ACTIVE->value => 'Active',
                        UserStatusEnum::INACTIVE->value => 'Inactive',
                        UserStatusEnum::PENDING->value => 'Pending',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
