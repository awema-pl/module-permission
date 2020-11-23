<?php

namespace AwemaPL\Permission\Controllers;

use AwemaPL\Permission\Repositories\Contracts\UserRepository;
use AwemaPL\Permission\Resources\EloquentRole;
use AwemaPL\Permission\Resources\EloquentUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AwemaPL\Permission\Requests\StoreRole;
use AwemaPL\Permission\Requests\AssignRoleToUser;
use AwemaPL\Permission\Repositories\Contracts\RoleRepository;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;

class RoleController extends Controller
{
    /**
     * Roles repository instance
     *
     * @var \AwemaPL\Permission\Repositories\Contracts\RoleRepository
     */
    protected $roles;


    /**
     * Permissions repository instance
     *
     * @var \AwemaPL\Permission\Repositories\Contracts\PermissionRepository
     */
    protected $permissions;

    /**
     * Users repository instance
     *
     * @var \AwemaPL\Permission\Repositories\Contracts\UserRepository
     */
    protected $users;

    public function __construct(RoleRepository $roles, PermissionRepository $permissions, UserRepository $users)
    {
        $this->roles = $roles;

        $this->permissions = $permissions;

        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roles->all();

        $permissions = $this->permissions->all();

        return view('awemapl-permission::roles.index', compact('roles', 'permissions'));
    }
    
    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentRole::collection(
            $this->roles->scope($request)
                ->latest()->smartPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        $this->roles->create($request->only('name'));

        return notify(_p('permission::notifies.roles.success_created_role', 'Success created role'));
    }

    /**
     * Assign role to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assign(AssignRoleToUser $request)
    {
        $this->roles->attachRole($request->email, $request->role_id);

        return notify(_p('permission::notifies.roles.success_assigned_role', 'Success assign role'));
    }

    /**
     * Remove role from user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revoke(AssignRoleToUser $request)
    {
        $this->roles->detachRole($request->email, $request->role_id);

        return notify(_p('permission::notifies.roles.success_revoked_role', 'Success revoked role'));
    }

    /**
     * Request all
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all(Request $request)
    {
        return EloquentRole::collection(
            $this->roles->scope($request)
                ->latest()->get()
        );
    }

    /**
     * Users scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function users(Request $request)
    {
        return EloquentUser::collection(
            $this->users->scope($request)->with('roles')
                ->latest()->smartPaginate()
        );
    }
}
