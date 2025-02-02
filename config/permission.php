<?php

return [

    'models' => [
        'user' => App\Models\Karyawan::class, // Ubah dari User ke Karyawan
        'role' => Spatie\Permission\Models\Role::class,
        'permission' => Spatie\Permission\Models\Permission::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'model_morph_key' => 'nik', // Gunakan 'nik' sebagai primary key di model_has_roles
    ],

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];
