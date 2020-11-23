<?php

namespace AwemaPL\Permission\Controllers\Installation;

use AwemaPL\Auth\Requests\SuperAdminStoreRequest;
use Illuminate\Http\Request;
use AwemaPL\Auth\Models\Country;
use AwemaPL\Auth\Services\Contracts\TwoFactor;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Requests\TwoFactorStoreRequest;
use AwemaPL\Auth\Requests\TwoFactorVerifyRequest;
use AwemaPL\Auth\Controllers\Controller;

class SuperAdminController extends Controller
{
    use RedirectsUsers, RedirectsTo;

    /**
     * Where to redirect users.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Show the form for assign super admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('awemapl-permission::installation.super_admin');
    }

    /**
     * Assign super admin to user.
     *
     * @param SuperAdminStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function assign(SuperAdminStoreRequest $request)
    {
        if ($request->ajax()) {
            $class= config('auth.providers.users.model');
            $user = $class::where('email', $request->email)->first();
            $user->assignRole(config('awemapl-permission.super_admin_role'));
            return $this->ajaxRedirectTo($request);
        }
    }
}
