<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Task;
use App\Models\Filter;
use Illuminate\Database\Eloquent\Builder;
use Closure;
use App\Settings\FilterSettings;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function baseTableQuery(): Builder
    {
        $user_role_id = \Auth::user()->roles()->first()->id;
        $subordinates_role_id = \Auth::user()->roles()->first()->descendants->pluck('id')->toArray();
        array_push($subordinates_role_id,$user_role_id);
        $filter = Filter::where('name','user_id')->first()->values->where('user_id',auth()->user()->id)->first();
        if($filter){
            $user_id = intval($filter->payload);
            return static::getResource()::getEloquentQuery()->whereIn('role_id',$subordinates_role_id)->where('user_id',$user_id);
        }
        else
            return static::getResource()::getEloquentQuery()->whereIn('role_id',$subordinates_role_id);
    }

    protected function getTableQuery(): Builder
    {
        return $this->baseTableQuery()->where('review',false);
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return function (Task $record) {

            if($record->done){
                return match (\Carbon\Carbon::parse($record->done_date)->gt($record->deadline)) {
                    true => 'opacity-70',
                    default => null,
                };
            }else{
                return match (\Carbon\Carbon::now()->startOfDay()->gt($record->deadline)) {
                    true => 'opacity-70',
                    default => null,
                };
            }

        };
    }
}
