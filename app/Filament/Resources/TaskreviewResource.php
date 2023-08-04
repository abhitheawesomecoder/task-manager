<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource;

class TaskreviewResource extends TaskResource
{
    protected static ?string $navigationLabel = 'Task for Review';

    protected static ?string $slug = 'task-for-review';

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\TaskreviewResource\Pages\ListTaskreviews::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }    
}
