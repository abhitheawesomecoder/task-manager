<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Tasks Pending', Task::where('review',false)->count()),
            Card::make('Tasks Done', Task::where('review',true)->where('done',true)->count()),
            Card::make('Tasks for Review', Task::where('done',true)->count()),
        ];
    }
}
