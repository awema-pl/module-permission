<?php

namespace AwemaPL\Permission\Controllers;

use AwemaPL\Permission\Resources\EloquentPermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AwemaPL\Permission\Requests\StorePermission;
use AwemaPL\Permission\Requests\AssignPermissionToRole;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * Permissions repository instance
     *
     * @var \AwemaPL\Permission\Repositories\Contracts\PermissionRepository
     */
    protected $permissions;

    public function __construct(PermissionRepository $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = $this->permissions->all();

        return view('awemapl-permission::permissions.index', compact('permissions'));
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentPermission::collection(
            $this->permissions->scope($request)
                ->latest()->smartPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermission $request)
    {
        $this->permissions->create($request->only('name'));

        return notify(_p('permission::notifies.permissions.success_created_permission', 'Success created permission'));
    }

    /**
     * Assign permission to role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assign(AssignPermissionToRole $request)
    {
        $this->permissions->attachPermission($request->role_id, $request->permission_id);

        return notify(_p('permission::notifies.permissions.success_assigned_permission', 'Success assigned permission'));
    }

    /**
     * Revoke permission from role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revoke(AssignPermissionToRole $request)
    {
        $this->permissions->detachPermission($request->role_id, $request->permission_id);

        return notify(_p('permission::notifies.permissions.success_revoked_permission', 'Success revoked permission'));
    }
}
