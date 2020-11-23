<?php

namespace AwemaPL\Permission;

use AwemaPL\Permission\Middlewares\Installation;
use AwemaPL\Permission\Repositories\Contracts\UserRepository;
use AwemaPL\Permission\Repositories\EloquentUserRepository;
use Exception;
use AwemaPL\Permission\Permission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use AwemaPL\Permission\Repositories\EloquentRoleRepository;
use AwemaPL\Permission\Repositories\Contracts\RoleRepository;
use AwemaPL\Permission\Repositories\EloquentPermissionRepository;
use AwemaPL\Permission\Contracts\Permission as PermissionContract;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PermissionServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
            ->pushMiddleware(Installation::class);

        $this->bootRouteMiddleware();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'permission');
        $this->bootRoutes();
        $this->bootGate();
        $this->publishes([
            __DIR__ . '/../config/awemapl-permission.php' => config_path('awemapl-permission.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../database/migrations/create_permission_tables.php.stub'
            => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_permission_tables.php'),
        ], 'migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'awemapl-permission');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/awemapl-permission'),
        ], 'views');
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerUserHasRoles();
        $this->mergeConfigFrom(__DIR__ . '/../config/awemapl-permission.php', 'awemapl-permission');
        $this->app->bind(PermissionContract::class, Permission::class);
        $this->app->singleton('permission', PermissionContract::class);
        $this->registerRepositories();
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(PermissionRepository::class, EloquentPermissionRepository::class);
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function registerUserHasRoles()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            $reflector = new \ReflectionClass($userClass);
            $path= $reflector->getFileName();
            $content = File::get($path);
            if (!Str::contains($content, 'use \AwemaPL\Permission\Traits\HasRoles;')){
                $content = Str::replaceFirst('{', '{' . PHP_EOL . PHP_EOL . "\t" . 'use \AwemaPL\Permission\Traits\HasRoles;', $content);
                File::put($path, $content);
            }
        }
    }
    
    public function bootRoutes()
    {
        if (config('awemapl-permission.routes.active')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }

    public function bootGate()
    {
        if (config('awemapl-permission.gates.superadmin_before')) {
            Gate::before(function ($user, $ability) {
                return $user->hasRole('super_admin') ? true : null;
            });
        } else {
            Gate::after(function ($user, $ability) {
                return $user->hasRole('super_admin');
            });
        }

    }

    public function bootRouteMiddleware()
    {
        app('router')->aliasMiddleware('role', \Spatie\Permission\Middlewares\RoleMiddleware::class);
        app('router')->aliasMiddleware('permission', \Spatie\Permission\Middlewares\PermissionMiddleware::class);
        app('router')->aliasMiddleware('role_or_permission', \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class);

    }
}
