<?php

namespace Tests\Feature\Notification;

use App\Notifications\Database\Role\RoleCreated;
use App\Notifications\Database\Role\RoleDeleted;
use App\Notifications\Database\Role\RoleUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\NotificationTestCase;

class AdminRoleNotificationTest extends NotificationTestCase
{
    use RefreshDatabase;

    protected Role $role;

    public function getUserPermissions(): array
    {
        return [
            'create role', 'edit role', 'delete role',
        ];
    }
    public function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'test']);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_role_is_created(): void
    {
        $this->post(route('roles.store'),   ['name' => 'test2', 'permissions' => ['create role']]);

        Notification::assertSentTo($this->admins, RoleCreated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_role_is_updated(): void
    {
        $this->put(
            route('roles.update', ['role' => $this->role->id]),
            ['name' => 'test'] + ['permissions' => ['create role']]
        );

        Notification::assertSentTo($this->admins, RoleUpdated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_role_is_deleted(): void
    {
        //delete the role
        $this->delete(
            route('roles.destroy', ['role' => $this->role->id])
        );

        Notification::assertSentTo($this->admins, RoleDeleted::class);
    }
}
