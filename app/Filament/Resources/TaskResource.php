<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('user_id')
                //     ->required(),
                Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\DatePicker::make('deadline')
                ->label('Target Date')
                ->default(now())
                ->required(),
                Select::make('priority')
                ->default('Medium')
                ->options([
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ]),
                Forms\Components\Section::make('Images')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('media')
                        ->collection('task-images')
                        ->multiple()
                        ->maxFiles(5)
                        ->disableLabel(),
                ])
                ->collapsible(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Toggle::make('done')
                    ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {// if role admin then show all tasks
     // if role not admin then show his assigned task all all his childrens task
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('done')
                    ->boolean(),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('deadline')->dateTime(),
                //Tables\Columns\TextColumn::make('updated_at')->dateTime(),
                Tables\Columns\ToggleColumn::make('review')->updateStateUsing(function ($state, $record) {
                    if ( $state == 1) {
                        $record->review = true;
                        $record->review_requested_by = auth()->id();
                        $record->save();
                    } else {
                        $record->review = false;
                        $record->review_requested_by = NULL;
                        $record->save();
                    }
                }),
                Tables\Columns\TextColumn::make('requested_by.name')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                ->multiple()
                ->options([
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
        
}
