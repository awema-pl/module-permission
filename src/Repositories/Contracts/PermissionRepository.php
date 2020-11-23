<?php

namespace AwemaPL\Permission\Repositories\Contracts;

interface PermissionRepository
{
    /**
     * Get all permissions with eager loaded related roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Create new permission
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Give permission to role
     *
     * @param integer $roleId
     * @param integer $permissionId
     * @return void
     */
    public function attachPermission($roleId, $permissionId);

    /**
     * Revoke permission from role
     *
     * @param integer $roleId
     * @param integer $permissionId
     * @return void
     */
    public function detachPermission($roleId, $permissionId);
}