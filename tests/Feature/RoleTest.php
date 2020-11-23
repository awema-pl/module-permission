<?php

namespace AwemaPL\Permission\Tests\Feature;

use Spatie\Permission\Models\Role;
use AwemaPL\Permission\Tests\TestCase;
use Spatie\Permission\Models\Permission;

class RoleTest extends TestCase
{
    protected $prefix;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->prefix = config('awemapl-permission.routes.roles_prefix');
    }

    /** @test */
    public function it_returns_index_view()
    {
        $this->get($this->prefix)
            ->assertViewIs('awemapl-permission::roles.index');
    }

    /** @test */
    public function it_returns_index_view_which_has_roles_data()
    {
        $this->get($this->prefix)
            ->assertViewHas('roles');
    }

    /** @test */
    public function it_returns_index_view_which_has_permissions_data()
    {
        $this->get($this->prefix)
            ->assertViewHas('permissions');
    }

    /** @test */
    public function it_requires_name_to_store_new_role()
    {
        $this->json('POST', $this->prefix, [
            //
        ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_requires_name_to_be_of_255_chars_max_to_store_new_role()
    {
        $this->json('POST', $this->prefix, [
            'name' => str_random(256)
        ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_requires_name_to_be_unique_to_store_new_role()
    {
        $this->json('POST', $this->prefix, [
            'name' => $name = uniqid()
        ])->assertRedirect();

        $this->json('POST', $this->prefix, [
            'name' => $name
        ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_stores_new_role()
    {
        $this->json('POST', $this->prefix, [
            'name' => $name = uniqid()
        ]);
        
        $this->assertDatabaseHas('roles', [
            'name' => $name
        ]);
    }

    /** @test */
    public function it_requires_role_id_to_assing_role_to_user()
    {
        $this->json('POST', $this->prefix . '/assign', [
            //
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_role_id_to_exist_to_assing_role_to_user()
    {
        $this->json('POST', $this->prefix . '/assign', [
            'role_id' => 1
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_email_to_assing_role_to_user()
    {
        $this->json('POST', $this->prefix . '/assign', [
            //
        ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function it_requires_email_to_exist_to_assing_role_to_user()
    {
        $this->json('POST', $this->prefix . '/assign', [
            'email' => 'email@example.com'
        ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function it_requires_role_id_to_revoke_role_from_user()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            //
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_role_id_to_exist_to_revoke_role_from_user()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            'role_id' => 1
        ])->assertJsonValidationErrors('role_id');
    }

    /** @test */
    public function it_requires_email_to_revoke_role_from_user()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            //
        ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function it_requires_email_to_exist_to_revoke_role_from_user()
    {
        $this->json('POST', $this->prefix . '/revoke', [
            'email' => 'email@example.com'
        ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function it_can_change_route_path_prefix()
    {
        $this->get($this->prefix)
            ->assertViewIs('awemapl-permission::roles.index');

        config()->set('awemapl-permission.routes.roles_prefix', $prefix = 'notpermissions/path');

        \AwemaPL\Permission\Facades\Permission::routes();

        $this->get($prefix)
            ->assertViewIs('awemapl-permission::roles.index');
    }

    /** @test */
    public function it_can_change_route_path_name_prefix()
    {
        config()->set('awemapl-permission.routes.roles_name_prefix', $prefix = 'new.name.prefix.');

        \AwemaPL\Permission\Facades\Permission::routes();

        $this->assertEquals(route($prefix . 'index', [], false), '/' . config('awemapl-permission.routes.roles_prefix'));
    }
}