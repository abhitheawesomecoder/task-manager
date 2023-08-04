<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource;

class TaskdoneResource extends TaskResource
{
    protected static ?string $navigationLabel = 'Task Done';

    protected static ?string $slug = 'task-done';

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\TaskdoneResource\Pages\ListTaskdones::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }    
}
