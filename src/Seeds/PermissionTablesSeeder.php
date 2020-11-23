<?php

namespace AwemaPL\Permission\Seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'owner', 'freelancer', 'manager', 'viewer'];

        foreach ($this->getPermissions() as $permission) {

            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {

            $role = Role::create(['name' => $role]);

            $this->assignPermissions($role);
        }
    }

    private function assignPermissions($role)
    {
        foreach ($this->getPermissions($role->name) as $permission) {

            $role->givePermissionTo($permission);
        }
    }

    private function getPermissions($type = 'all')
    {
        $everybody = [
            'view dashboard',

            'list customers',
            'view customers',
            'create customers',
            'update customers',
            'delete customers',

            'list creditors',
            'view creditors',
            'create creditors',
            'update creditors',
            'delete creditors',

            'list managers',
            'view managers [me]',
            'update managers [me]',
        ];

        $owner = array_merge($everybody, [
            'view overview',
            'list projects',
            'view analytics',
            'view managers',
            'create managers',
            'update managers',
            'delete managers',
            'view settings',
            'view billing',
            'view billing dashboard',
            'view billing plans',
            'view billing method',
            'view billing history',
            'view billing settings'
        ]);

        $admin = array_merge(['admin'], $owner);

        switch ($type) {
            case 'all':

            case 'admin':
                return $admin;
                break;

            case 'owner':

            case 'freelancer':
                return $owner;
                break;

            case 'manager':
            
            case 'viewer':

            default:
                return $everybody;
                break;
        }
    }
}
