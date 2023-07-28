<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Closure;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user_role_id = \Auth::user()->roles()->first()->id;
        $subordinates_role_id = \Auth::user()->roles()->first()->descendants->pluck('id')->toArray();
        array_push($subordinates_role_id,$user_role_id);
        return static::getResource()::getEloquentQuery()->whereIn('role_id',$subordinates_role_id);
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return fn (Task $record) => match (\Carbon\Carbon::now()->startOfDay()->gte($record->deadline)) {
            true => 'opacity-70',
            default => null,
        };
    }
}
