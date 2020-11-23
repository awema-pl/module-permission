<?php

namespace AwemaPL\Permission\Tests\Unit;

use Spatie\Permission\Models\Role;
use AwemaPL\Permission\Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;

class PermissionRepositoryTest extends TestCase
{
    protected $permissions;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->permissions = app(PermissionRepository::class);
    }

    /** @test */
    public function it_returns_collection_of_permissions()
    {
        $permission = factory(Permission::class)->create();

        $permissions = $this->permissions->all();

        $this->assertInstanceOf(Collection::class, $permissions);
    }

    /** @test */
    public function it_returns_existing_permissions()
    {
        $permission = factory(Permission::class)->create();

        $permissions = $this->permissions->all();

        $this->assertCount(1, $permissions);

        $this->assertEquals($permission->name, $permissions->first()->name);
    }

    /** @test */
    public function it_returns_collection_of_related_roles()
    {
        $permission = factory(Permission::class)->create();

        $permissions = $this->permissions->all();

        $this->assertInstanceOf(Collection::class, $permissions->first()->roles);
    }

    /** @test */
    public function it_creates_new_permission()
    {
        $this->permissions->create([
            'name' => $name = str_random()
        ]);

        $this->assertDatabaseHas('permissions', [
            'name' => $name
        ]);
    }

    /** @test */
    public function it_can_attach_permission_role()
    {
        $permission = factory(Permission::class)->create();

        $role = factory(Role::class)->create();

        $this->permissions->attach($role->id, $permission->id);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_detach_permission_role()
    {
        $permission = factory(Permission::class)->create();

        $role = factory(Role::class)->create();

        $this->permissions->attach($role->id, $permission->id);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);

        $this->permissions->detach($role->id, $permission->id);

        $this->assertDatabaseMissing('role_has_permissions', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);
    }
}