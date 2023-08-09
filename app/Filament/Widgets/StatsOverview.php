<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\Filter;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $filter = Filter::where('name','user_id')->first()->values->where('user_id',auth()->user()->id)->first();
        if($filter){
            $user_id = intval($filter->payload);
            return [
                Card::make('Tasks Pending', Task::where('review',false)->where('user_id',$user_id)->count()),
                Card::make('Tasks Done', Task::where('done',true)->where('user_id',$user_id)->count()),
                Card::make('Tasks for Review', Task::where('review',true)->where('done',false)->where('user_id',$user_id)->count()),
            ];
        }
        return [
            Card::make('Tasks Pending', Task::where('review',false)->count()),
            Card::make('Tasks Done', Task::where('done',true)->count()),
            Card::make('Tasks for Review', Task::where('review',true)->where('done',false)->count()),
        ];
        
    }
}
