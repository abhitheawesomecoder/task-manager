<?php

namespace App\Filament\Resources\TaskreviewResource\Pages;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages\ListTasks;

class ListTaskreviews extends ListTasks
{
    protected function getTableQuery(): Builder
    {
        return $this->baseTableQuery()->where('review',true)->where('done',false);
    }
}
