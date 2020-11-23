<?php

namespace AwemaPL\Permission;

use AwemaPL\Permission\Traits\HasRoles;
use DocDigital\Lib\SourceEditor\PhpClassEditor;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Permission\Contracts\Permission as PermissionContract;
use Illuminate\Support\Str;
use Exception;

class Permission implements PermissionContract
{
    /**
     * Register routes.
     *
     * @return void
     */
    public function routes()
    {
        if (config('awemapl-permission.routes.active')){
            $this->roleRoutes();

            $this->permissionRoutes();

            // Installation permission Routes...
            if ($this->isInstallationPermissionEnabled() && $this->isExistUserInDatabase() && !$this->isExistPermissionSuperAdminInDatabase()){
                $this->installationPermissionRoutes();
            }
        }
    }

    /**
     * Check if installtion permission eneabled in config
     *
     * @return boolean
     */
    public function isInstallationPermissionEnabled()
    {
        return in_array('permission', config('awemapl-permission.installation.sections'));
    }

    /**
     * Register role's routes.
     *
     * @return void
     */
    protected function roleRoutes()
    {
        $router = app('router');

        $prefix = config('awemapl-permission.routes.roles_prefix');

        $namePrefix = config('awemapl-permission.routes.roles_name_prefix');
        
        Route::prefix($prefix)->name($namePrefix)->middleware(['web', 'auth', 'can:manage_permissions'])->group(function () use ($router) {

            $router->get('/', '\AwemaPL\Permission\Controllers\RoleController@index')
                ->name('index');

            $router->get('scope', '\AwemaPL\Permission\Controllers\RoleController@scope')
                ->name('scope');

            $router->get('all', '\AwemaPL\Permission\Controllers\RoleController@all')
                ->name('all');

            $router->post('/', '\AwemaPL\Permission\Controllers\RoleController@store')
                ->name('store');

            $router->post('assign', '\AwemaPL\Permission\Controllers\RoleController@assign')
                ->name('assign');

            $router->post('revoke', '\AwemaPL\Permission\Controllers\RoleController@revoke')
                ->name('revoke');

            $router->get('users', '\AwemaPL\Permission\Controllers\RoleController@users')
                ->name('users');
        });
    }

    /**
     * Register permission's routes.
     *
     * @return void
     */
    protected function permissionRoutes()
    {
        $router = app('router');

        $prefix = config('awemapl-permission.routes.permissions_prefix');

        $namePrefix = config('awemapl-permission.routes.permissions_name_prefix');
        
        Route::prefix($prefix)->name($namePrefix)->middleware(['web', 'auth', 'can:manage_permissions'])->group(function () use ($router) {

            $router->get('/', '\AwemaPL\Permission\Controllers\PermissionController@index')
                ->name('index');

            $router->get('scope', '\AwemaPL\Permission\Controllers\PermissionController@scope')
                ->name('scope');

            $router->post('/', '\AwemaPL\Permission\Controllers\PermissionController@store')
                ->name('store');

            $router->post('assign', '\AwemaPL\Permission\Controllers\PermissionController@assign')
                ->name('assign');

            $router->post('revoke', '\AwemaPL\Permission\Controllers\PermissionController@revoke')
                ->name('revoke');
        });
    }

    /**
     * Add installation permission routes
     */
    public function installationPermissionRoutes()
    {
        $router = app('router');

        $router->get(
            'installation/permission/super-admin',
            '\AwemaPL\Permission\Controllers\Installation\SuperAdminController@index'
        )->name('permission.installation.super_admin.index');

        $router->post(
            'installation/permission/super-admin/assign',
            '\AwemaPL\Permission\Controllers\Installation\SuperAdminController@assign'
        )->name('permission.installation.super_admin.assign');
    }

    /**
     * Check is exist user in database
     *
     * @return bool
     */
    public function isExistUserInDatabase()
    {
        try {
            $class= config('auth.providers.users.model');
            return !!$class::count();
        } catch (Exception $e){}
        return false;
    }

    /**
     * Check is exist permission super admin in database
     *
     * @return bool
     */
    public function isExistPermissionSuperAdminInDatabase()
    {
        $class= config('auth.providers.users.model');
        try {
            return !!$class::role(config('awemapl-permission.super_admin_role'))->count();
        } catch (Exception $e){
            return false;
        }
    }
}
