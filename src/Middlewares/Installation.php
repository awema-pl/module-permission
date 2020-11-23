<?php

namespace AwemaPL\Permission\Middlewares;

use AwemaPL\Auth\Facades\Auth;
use AwemaPL\Permission\Facades\Permission;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Installation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() === 'GET' && config('awemapl-permission.routes.active') && Permission::isInstallationPermissionEnabled() && Permission::isExistUserInDatabase() && !Permission::isExistPermissionSuperAdminInDatabase()){
            $route = Route::getRoutes()->match($request);
            $name = $route->getName();
            if (!in_array($name, config('awemapl-permission.installation.expect.routes'))){
                return redirect()->route('permission.installation.super_admin.index');
            }
        }
        return $next($request);
    }
}