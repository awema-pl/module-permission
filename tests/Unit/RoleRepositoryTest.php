<?php

namespace AwemaPL\Permission\Tests\Unit;

use Spatie\Permission\Models\Role;
use AwemaPL\Permission\Tests\TestCase;
use AwemaPL\Permission\Tests\Stubs\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use AwemaPL\Permission\Repositories\Contracts\RoleRepository;

class RoleRepositoryTest extends TestCase
{
    protected $roles;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->roles = app(RoleRepository::class);
    }

    /** @test */
    public function it_returns_collection_of_roles()
    {
        $role = factory(Role::class)->create();

        $roles = $this->roles->all();

        $this->assertInstanceOf(Collection::class, $roles);
    }

    /** @test */
    public function it_returns_existing_roles()
    {
        $role = factory(Role::class)->create();

        $roles = $this->roles->all();

        $this->assertCount(1, $roles);

        $this->assertEquals($role->name, $roles->first()->name);
    }

    /** @test */
    public function it_returns_collection_of_related_permissions()
    {
        $role = factory(Role::class)->create();

        $roles = $this->roles->all();

        $this->assertInstanceOf(Collection::class, $roles->first()->permissions);
    }

    /** @test */
    public function it_creates_new_roles()
    {
        $this->roles->create([
            'name' => $name = str_random()
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => $name
        ]);
    }

    /** @test */
    public function it_can_attach_role_to_user()
    {
        $role = factory(Role::class)->create();

        $this->roles->user = User::class;

        $user = factory(User::class)->create();

        $this->roles->attach($user->email, $role->id);

        $this->assertDatabaseHas('model_has_roles', [
            'model_id' => $user->id,
            'model_type' => 'AwemaPL\Permission\Tests\Stubs\User',
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_detach_role_to_user()
    {
        $role = factory(Role::class)->create();

        $this->roles->user = User::class;

        $user = factory(User::class)->create();

        $this->roles->attach($user->email, $role->id);

        $this->assertDatabaseHas('model_has_roles', [
            'model_id' => $user->id,
            'model_type' => 'AwemaPL\Permission\Tests\Stubs\User',
            'role_id' => $role->id,
        ]);

        $this->roles->detach($user->email, $role->id);

        $this->assertDatabaseMissing('model_has_roles', [
            'model_id' => $user->id,
            'model_type' => 'AwemaPL\Permission\Tests\Stubs\User',
            'role_id' => $role->id,
        ]);
    }
}