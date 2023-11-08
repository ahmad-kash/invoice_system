<?php

namespace Tests\Feature\Permission;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Spatie\Permission\Models\Role;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $spyUser;

    public function setUp(): void
    {
        parent::setUp();

        (new PermissionRoleTestFactory)->createPermissions([
            'create role',
            'show role',
            'delete role',
            'edit role'
        ]);

        $this->spyUser = $this->spy(User::class, function (MockInterface $mock) {
            $mock->shouldReceive('isActive')->andReturn(true);
        });

        $this->signIn($this->spyUser);
    }

    /** @test */
    public function user_is_asked_if_he_has_show_role_permission_on_route_roles_index(): void
    {
        $this->get(route('roles.index'));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('show role')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_create_role_permission_on_route_roles_store(): void
    {
        $role = ['name' => 'test', 'permissions' => ['create role']];

        $this->post(route('roles.store'), $role);

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('create role')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_edit_role_permission_on_route_roles_update(): void
    {
        $role = Role::create(['name' => 'test']);
        $roleData = ['name' => 'test', 'permissions' => ['create role']];

        $this->put(route('roles.update', ['role' => $role->id]), $roleData);

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('edit role')->once();
    }

    /** @test */
    public function user_is_asked_if_he_has_delete_role_permission_on_route_roles_delete(): void
    {
        $role = Role::create(['name' => 'test']);

        $this->delete(route('roles.destroy', ['role' => $role->id]));

        $this->spyUser->shouldHaveReceived('hasPermissionTo')->with('delete role')->once();
    }
}
