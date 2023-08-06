<?php

namespace App\Filament\Resources\TaskdoneResource\Pages;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages\ListTasks;

class ListTaskdones extends ListTasks
{
    protected function getTableQuery(): Builder
    {
        return $this->baseTableQuery()->where('review',true)->where('done',true);
    }
}
