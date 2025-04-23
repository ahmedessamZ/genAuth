<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Admin Information')->schema([
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
                    Forms\Components\Select::make('status')
                        ->boolean()
                        ->options([
                            '1' => 'Active',
                            '0' => 'Inactive',
                        ])
                        ->default('0')
                        ->required()
                        ->label('Account Status')
                        ->columnSpan(1),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection('admin-avatar')
                        ->columnSpan('full'),
                ])->columns(2)->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function (Admin $query) {
                $loggedInAdminId = Auth::id();
                return $query->where('id', '!=', $loggedInAdminId);
            })
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('admin-avatar'),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable(),
                Tables\Columns\BooleanColumn::make('status'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('activation')
                    ->label('Account Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
