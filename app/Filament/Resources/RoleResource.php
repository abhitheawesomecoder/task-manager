<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\Pages\CreateRole;
use Phpsa\FilamentAuthentication\Resources\RoleResource\Pages\EditRole;
use Phpsa\FilamentAuthentication\Resources\RoleResource\Pages\ListRoles;
use Phpsa\FilamentAuthentication\Resources\RoleResource\Pages\ViewRole;
use Phpsa\FilamentAuthentication\Resources\RoleResource as NestedRoleResource;

class RoleResource extends NestedRoleResource
{
    protected static ?string $model = Role::class;

    // protected static ?string $navigationIcon = 'heroicon-o-collection';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(strval(__('filament-authentication::filament-authentication.field.name')))
                                    ->required(),
                                TextInput::make('guard_name')
                                    ->label(strval(__('filament-authentication::filament-authentication.field.guard_name')))
                                    ->required()
                                    ->default(config('auth.defaults.guard')),
                                Select::make('parent_id')
                                    ->label('Parent')
                                    ->options(Role::all()->pluck('name', 'id'))
                                    ->searchable()
                                // BelongsToManyMultiSelect::make('permissions')
                                //     ->label(strval(__('filament-authentication::filament-authentication.field.permissions')))
                                //     ->relationship('permissions', 'name')
                                //     ->hidden()
                                //     ->preload(config('filament-spatie-roles-permissions.preload_permissions'))
                            ]),
                    ]),
            ]);
    }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             //
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\DeleteBulkAction::make(),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label(strval(__('filament-authentication::filament-authentication.field.name')))
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label(strval(__('filament-authentication::filament-authentication.field.parent')))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->label(strval(__('filament-authentication::filament-authentication.field.guard_name')))
                    ->searchable(),
            ])
            ->filters([
                //
            ]);
    }
    
    // public static function getRelations(): array
    // {
    //     return [
    //         //
    //     ];
    // }
    
    // public static function getPages(): array
    // {
    //     return [
    //         'index' => Pages\ListRoles::route('/'),
    //         'create' => Pages\CreateRole::route('/create'),
    //         'edit' => Pages\EditRole::route('/{record}/edit'),
    //     ];
    // }    

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
            'view' => ViewRole::route('/{record}'),
        ];
    }
}
