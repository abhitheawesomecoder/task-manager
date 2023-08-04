<?php

namespace App\Filament\Resources\TaskdoneResource\Pages;

use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages\ListTasks;

class ListTaskdones extends ListTasks
{
    protected function getTableQuery(): Builder
    {
        $user_role_id = \Auth::user()->roles()->first()->id;
        $subordinates_role_id = \Auth::user()->roles()->first()->descendants->pluck('id')->toArray();
        array_push($subordinates_role_id,$user_role_id);
        return static::getResource()::getEloquentQuery()->whereIn('role_id',$subordinates_role_id)->where('review',true)->where('done',true);
    }
}
