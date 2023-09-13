<?php

namespace Tests\Feature\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\DashboardTestCase;

class UserTest extends DashboardTestCase
{

    public function getPermissions(): array
    {
        return [
            'edit user',
            'show user', 'update user',
            'delete user', 'show all users',
            'force delete user',
            'reset password'
        ];
    }

    /** @test */
    public function admin_can_see_all_users(): void
    {
        $users = User::factory(4)->create();
        $this->get(route('users.index'))
            ->assertOk()
            ->assertViewIs('user.index')
            ->assertViewHas('users', function ($paginator) use ($users) {
                return $this->eloquentCollectionsAreEqual(collect($paginator->items()), collect([$this->user, ...$users]), ['id']);
            })
            ->assertSee('قائمة المستخدمين');
    }

    /** @test */
    public function admin_can_see_update_user_page(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'user']);
        // use must have a role
        $user->assignRole('user');



        $this->get(route('users.edit', ['user' => $user->id]))
            ->assertViewHasAll(['user', 'roles'])
            ->assertViewIs('user.edit')
            ->assertSee('تعديل بيانات المستخدم');
    }
    /** @test */
    public function admin_can_update_user_data(): void
    {
        $user = User::factory()->create();

        $newUserData = [
            'email' => 'newEmail@test.com',
            'is_active' => false,
            'name' => 'new name',
        ];
        $this->put(route('users.update', ['user' => $user->id]), $newUserData)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id] + $newUserData);
    }

    /** @test */
    public function admin_can_delete_user(): void
    {
        $user = User::factory()->create();

        $this->delete(route('users.destroy', ['user' => $user->id]))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => now()]);
    }

    /** @test */
    public function admin_can_force_delete_user(): void
    {
        $user = User::factory()->create();

        $this->delete(route('users.forceDestroy', ['user' => $user->id]))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function admin_can_reset_user_password(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        //redirect back
        $this->from(route('users.index', ['page' => 3]))->put(route('password.reset.update', ['user' => $user->id]))
            ->assertRedirect(route('users.index', ['page' => 3]));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'email_verified_at' => null]);
        Mail::assertSent(WelcomeMail::class);
    }
}
