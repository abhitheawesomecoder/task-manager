<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        $user = User::find($data['user_id']);
        
        $role = $user->roles ? $user->roles->first() : $user->roles;

        $data['role_id'] = $role ? $role->id : 1; // if role not assigned to user then task assigned to admin as a default

        return static::getModel()::create($data);
    }
}
