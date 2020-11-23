<?php

namespace AwemaPL\Permission\Tests\Feature;

use AwemaPL\Permission\Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTest extends TestCase
{
    protected $prefix;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->prefix = config('awemapl-permission.routes.permissions_prefix');
    }

    /** @test */
    public function it_returns_index_view()
    {
        $this->get($this->prefix)
            ->assertViewIs('awemapl-permission::permissions.index');
    }

    /** @test */
    public function it_returns_index_view_which_has_permissions_data()
    {
        $this->get($this->prefix)
            ->assertViewHas('permissions');
    }

    /** @test */
    public function it_requires_name_to_store_new_permission()
    {
        $this->json('POST', $this->prefix, [
            //
        ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_requires_name_to_be_of_255_chars_max_to_store_new_permission()
    {
        $this->json('POST', $this->prefix, [
            'name' => str_random(256)
        ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_requires_name_to_be_unique_to_store_new_permission()
    {
        $this->json('POST', $this->prefix, [
            'name' => $name = uniqid()
        ])->assertRedirect();

        $this->json('POST', $this->prefix, [
            'name' => $name
        ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_stores_new_permission()
    {
        $this->json('POST', $this->prefix, [
            'name' => $name = uniqid()
        ]);
        
        $this->assertDatabaseHas('permissions', [
            'name' => $name
        ]);
    }

    /** @test */
    public function it_requires_role_id_to_assing_permission_to_role()
    {
        $this->json('POST', $this->prefix . '/assign', [
            //
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_role_id_to_exist_to_assing_permission_to_role()
    {
        $this->json('POST', $this->prefix . '/assign', [
            'role_id' => 1
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_permission_id_to_assing_permission_to_role()
    {
        $this->json('POST', $this->prefix . '/assign', [
            //
        ])->assertJsonValidationErrors('permission_id');
    }


    /** @test */
    public function it_requires_permission_id_to_exist_to_assing_permission_to_role()
    {
        $this->json('POST', $this->prefix . '/assign', [
            'permission_id' => 1
        ])->assertJsonValidationErrors('permission_id');
    }

    /** @test */
    public function it_assigns_permission_to_role()
    {
        $role = factory(Role::class)->create();

        $permission = factory(Permission::class)->create();

        $this->json('POST', $this->prefix . '/assign', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
        
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }

    /** @test */
    public function it_requires_role_id_to_revoke_permission_from_role()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            //
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_role_id_to_exist_to_revoke_permission_from_role()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            'role_id' => 1
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_permission_id_to_revoke_permission_from_role()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            //
        ])->assertJsonValidationErrors('permission_id');
    }


    /** @test */
    public function it_requires_permission_id_to_exist_to_revoke_permission_from_role()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            'permission_id' => 1
        ])->assertJsonValidationErrors('permission_id');
    }

    /** @test */
    public function it_revokes_permission_from_role()
    {
        $role = factory(Role::class)->create();

        $permission = factory(Permission::class)->create();

        $this->json('POST', $this->prefix . '/assign', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
        
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);

        $this->json('POST', $this->prefix . '/revoke', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
        
        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }

    /** @test */
    public function it_can_change_route_path_prefix()
    {
        $this->get($this->prefix)
            ->assertViewIs('awemapl-permission::permissions.index');

        config()->set('awemapl-permission.routes.permissions_prefix', $prefix = 'notpermissions/path');

        \AwemaPL\Permission\Facades\Permission::routes();

        $this->get($prefix)
            ->assertViewIs('awemapl-permission::permissions.index');
    }

    /** @test */
    public function it_can_change_route_path_name_prefix()
    {
        config()->set('awemapl-permission.routes.permissions_name_prefix', $prefix = 'new.name.prefix.');

        \AwemaPL\Permission\Facades\Permission::routes();

        $this->assertEquals(route($prefix . 'index', [], false), '/' . config('awemapl-permission.routes.permissions_prefix'));
    }
}