<?php

namespace Tests\Feature\Permission;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;
use Tests\UserTestBuilder;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $unauthorizedUser;
    protected User $authorizedUser;

    public function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'create user',
            'edit user',
            'show user',
            'delete user',
            'restore user',
            'force delete user',
            'reset password'
        ];
        (new PermissionRoleTestFactory)->createPermissions($permissions);

        $this->unauthorizedUser = User::factory()->create();

        $this->authorizedUser = (new UserTestBuilder())->withPermissions($permissions)->create();

        $this->signIn($this->unauthorizedUser);
    }

    /** @test */
    public function user_is_asked_if_he_has_show_user_permission_on_route_users_index(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);
        $this->get(route('users.index'));

        $this->signIn($this->authorizedUser);

        $this->get(route('users.index'))
            ->assertViewIs('user.index');
    }

    /** @test */
    public function user_is_asked_if_he_has_create_user_permission_on_route_users_store(): void
    {
        $user = User::factory()->make();
        $role = Role::create(['name' => 'admin']);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->post(route('users.store'), $user->toArray() + ['role' => 'admin']);

        $this->signIn($this->authorizedUser);


        $this->post(route('users.store'), $user->toArray() + ['role' => 'admin'])
            ->assertRedirect(route('user.index'));
    }

    /** @test */
    public function user_is_asked_if_he_has_edit_user_permission_on_route_users_update(): void
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->put(route(
            'users.update',
            ['user' => $user->id]
        ), $user->toArray());

        $this->signIn($this->authorizedUser);


        $this->put(route(
            'users.update',
            ['user' => $user->id]
        ), $user->toArray())
            ->assertRedirect(route('user.index'));
    }

    /** @test */
    public function user_is_asked_if_he_has_delete_user_permission_on_route_users_delete(): void
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->delete(route('users.destroy', ['user' => $user->id]));

        $this->signIn($this->authorizedUser);


        $this->delete(route('users.destroy', ['user' => $user->id]))
            ->assertRedirect(route('user.index'));
    }
    /** @test */
    public function user_is_asked_if_he_has_restore_user_permission_on_route_users_restore(): void
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);
        $this->put(route('users.restore', ['user' => $user->id]));

        $this->signIn($this->authorizedUser);

        $this->delete(route('users.restore', ['user' => $user->id]))
            ->assertRedirect();
    }

    /** @test */
    public function user_is_asked_if_he_has_restore_user_permission_on_route_users_arcive_index(): void
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);
        $this->get(route('users.archive.index'));

        $this->signIn($this->authorizedUser);

        $this->get(route('users.archive.index'));
    }

    /** @test */
    public function user_is_asked_if_he_has_force_delete_user_permission_on_route_users_delete(): void
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->delete(route('users.forceDestroy', ['user' => $user->id]));

        $this->signIn($this->authorizedUser);


        $this->delete(route('users.forceDestroy', ['user' => $user->id]))
            ->assertRedirect();
    }

    /** @test */
    public function user_is_asked_if_he_has_reset_password_permission_on_route_reset_password(): void
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this->put(route('password.reset.update', ['user' => $user->id]));

        $this->signIn($this->authorizedUser);


        $this->put(route('password.reset.update', ['user' => $user->id]))
            ->assertRedirect();
    }
}
