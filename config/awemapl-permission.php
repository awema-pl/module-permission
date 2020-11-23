<?php
return [
    // routes related parameters
    'routes' => [
        // role's routes path prefix
        'roles_prefix' => 'admin/roles',
        // role's routes name prefix
        'roles_name_prefix' => 'admin.roles.',
        // permission's routes path prefix
        'permissions_prefix' => 'admin/permissions',
        // permission's routes name prefix
        'permissions_name_prefix' => 'admin.permissions.',
        // active routes
        'active' => true,
    ],
    'super_admin_role' => 'super_admin',

    'gates' => [
        'superadmin_before' => false,
    ],

    // default insert roles with migrate of database
    'insert_roles' => [],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'manage_permissions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Installation application.
    |--------------------------------------------------------------------------
    */
    'installation' => [
        // Assign super admin to first user - section `permission`
        'sections' => ['permission'],

        // except for redirect to installation
        'expect' => [
            'routes' => [
                'permission.installation.super_admin.index',
                'module-assets.assets',
                'permission.installation.super_admin.assign',
            ]
        ]
    ],
];
