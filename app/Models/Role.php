<?php
namespace App\Models;
use Spatie\Permission\Models\Role as SpatieRole;
use Kalnoy\Nestedset\NodeTrait;

class Role extends SpatieRole
{
    use NodeTrait;

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}