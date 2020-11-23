<?php

namespace AwemaPL\Permission\Repositories;

use AwemaPL\Permission\Scopes\EloquentPermissionScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use NetLinker\HelpStartup\Sections\Accounts\Models\Account;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;

class EloquentPermissionRepository extends BaseRepository implements PermissionRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Permission::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentPermissionScopes($request))->scope($this->entity);

        return $this;
    }
    
    /**
     * Get all permissions with eager loaded related roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Permission::with('roles')->get();
    }

    /**
     * Create new permission
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return Permission::create($data);
    }

    /**
     * Give permission to role
     *
     * @param integer $roleId
     * @param integer $permissionId
     * @return void
     */
    public function attachPermission($roleId, $permissionId)
    {
        $role = Role::find($roleId);

        $permission = Permission::find($permissionId);

        $role->givePermissionTo($permission);
    }

    /**
     * Revoke permission from role
     *
     * @param integer $roleId
     * @param integer $permissionId
     * @return void
     */
    public function detachPermission($roleId, $permissionId)
    {
        $role = Role::find($roleId);

        $permission = Permission::find($permissionId);

        $role->revokePermissionTo($permission);
    }
}