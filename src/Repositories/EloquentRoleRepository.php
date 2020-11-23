<?php

namespace AwemaPL\Permission\Repositories;

use AwemaPL\Permission\Scopes\EloquentRoleScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use NetLinker\HelpStartup\Sections\Accounts\Scopes\AccountScopes;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use AwemaPL\Permission\Repositories\Contracts\RoleRepository;

class EloquentRoleRepository extends BaseRepository implements RoleRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Role::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentRoleScopes($request))->scope($this->entity);

        return $this;
    }
    /**
     * Get all roles with eager loaded related permissions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Role::with('permissions')->get();
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return Role::create($data);
    }

    /**
     * Assign role to user
     *
     * @param string $userEmail
     * @param integer $permissionId
     * @return void
     */
    public function attachRole($userEmail, $roleId)
    {
        $user = config('auth.providers.users.model')::where('email', $userEmail)->first();

        $role = Role::find($roleId);

        $user->assignRole($role);
    }

    /**
     * Remove role from user
     *
     * @param string $userEmail
     * @param integer $permissionId
     * @return void
     */
    public function detachRole($userEmail, $roleId)
    {
        $user = config('auth.providers.users.model')::where('email', $userEmail)->first();

        $role = Role::find($roleId);

        $user->removeRole($role);
    }
}