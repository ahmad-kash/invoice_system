<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Tests\DashboardTestCase;

class UserArchiveTest extends DashboardTestCase
{

    public function getPermissions(): array
    {
        return
            [
                'create user',
                'show user',
                'delete user',
                'edit user',
                'force delete user',
                'restore user',
            ];
    }


    /** @test */
    public function user_can_soft_delete_user(): void
    {
        $user = User::factory()->create();

        $this->delete(route('users.destroy', ['user' => $user->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => $user->email, 'deleted_at' => now()]);
    }

    /** @test */
    public function user_can_restore_soft_deleted_user(): void
    {
        $user = User::factory()->create();
        $this->withoutExceptionHandling();

        $this->delete(route('users.destroy', ['user' => $user->id]));
        $this->put(route('users.restore', ['user' => $user->id]))
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => $user->email, 'deleted_at' => null]);
    }

    /** @test */
    public function user_can_see_archive_user_page(): void
    {
        $users = User::factory(2)->create();
        $archivedUsers = User::factory(2)->create(['deleted_at' => now()]);

        $this->get(route('users.archive.index'))
            ->assertOk()
            ->assertViewHas('users', function ($paginator) use ($archivedUsers) {
                return $this->eloquentCollectionsAreEqual(collect($paginator->items()), $archivedUsers);
            })
            ->assertSeeInOrder($archivedUsers->map(fn ($user) => $user->email)->toArray());
    }
}
