<?php

namespace AwemaPL\Permission\Repositories\Contracts;

interface RoleRepository
{
    /**
     * Get all roles with eager loaded related permissions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Assign role to user
     *
     * @param string $userEmail
     * @param integer $permissionId
     * @return void
     */
    public function attachRole($userEmail, $roleId);

    /**
     * Remove role from user
     *
     * @param string $userEmail
     * @param integer $permissionId
     * @return void
     */
    public function detachRole($userEmail, $roleId);
}