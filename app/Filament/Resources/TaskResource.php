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
use Carbon\Carbon;
use Webbingbrasil\FilamentDateFilter\DateFilter;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

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
    {
        $filterArray = [
            Tables\Filters\SelectFilter::make('priority')
            ->multiple()
            ->options([
                'High' => 'High',
                'Medium' => 'Medium',
                'Low' => 'Low',
            ]),
            Tables\Filters\Filter::make('done')
            ->label('Deadline miss')
            ->query(fn (Builder $query): Builder => $query->where('done', true)->where('done_date','<','deadline')->orWhere(function (Builder $query) {
                $query->where('done', false)
                      ->whereDate('deadline' , '<',Carbon::now()->startOfDay() );
            })),
            DateFilter::make('deadline')
            ->label(__('Deadline'))
            ->minDate(Carbon::today()->subMonth(1))
            ->maxDate(Carbon::today()->addMonth(1))
            ->timeZone('Asia/Kolkata')
            ->range()
            ->fromLabel(__('From'))
            ->untilLabel(__('Until'))
        ];

        if(auth()->user()->can('tasks.filter.user') || auth()->user()->can('authentication'))
          array_push($filterArray,Tables\Filters\SelectFilter::make('user')->relationship('user', 'name'));
        
     // if role admin then show all tasks
     // if role not admin then show his assigned task all all his childrens task
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('done')
                    ->boolean(),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('deadline')->date(),
                Tables\Columns\TextColumn::make('done_date')->date(),
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
            ->filters($filterArray)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                FilamentExportBulkAction::make('export')->withColumns([Tables\Columns\TextColumn::make('description')])
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
