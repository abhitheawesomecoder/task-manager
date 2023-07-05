<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Pages\Actions;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use Phpsa\FilamentAuthentication\Resources\RoleResource\Pages\CreateRole as NestedCreateRole;

class CreateRole extends NestedCreateRole
{
    //protected static string $resource = RoleResource::class;
    protected function handleRecordCreation(array $data): Model
    {

        if($data['parent_id'])
        {
            //$parent_node = static::getModel()::find(1);
            $parent_node = Role::find($data['parent_id']);
            return Role::create($data, $parent_node);
        }else
          return Role::create($data);
        //dd($parent_node);
        //return static::getModel()::create($data, $parent_node);
    }
}
