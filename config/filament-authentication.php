<?php

return [
    'models' => [
        'User'       => \App\Models\User::class,
        'Role'       => \App\Models\Role::class,
        'Permission' => \Spatie\Permission\Models\Permission::class,
    ],
    'resources'     => [
        'UserResource'       => \App\Filament\Resources\UserResource::class,
        'RoleResource'       => \App\Filament\Resources\RoleResource::class,
        'PermissionResource' => \Phpsa\FilamentAuthentication\Resources\PermissionResource::class,
    ],
    'pages'         => [
        'Profile' => \Phpsa\FilamentAuthentication\Pages\Profile::class
    ],
    'Widgets'       => [
        'LatestUsers' => [
            'enabled' => false,
            'limit'   => 5,
            'sort'    => 0,
            'paginate' => false
        ]
    ],
    'preload_roles' => true,
    'impersonate'   => [
        'enabled'  => true,
        'guard'    => 'web',
        'redirect' => '/'
    ]
];
