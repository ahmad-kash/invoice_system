<?php

namespace Tests\Feature\Notification;

use App\Models\User;
use App\Notifications\Database\User\UserCreated;
use App\Notifications\Database\User\UserDeleted;
use App\Notifications\Database\User\UserForceDeleted;
use App\Notifications\Database\User\UserResetPassword;
use App\Notifications\Database\User\UserUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\NotificationTestCase;

class AdminUserNotificationTest extends NotificationTestCase
{

    use RefreshDatabase;

    protected User $newUser;

    public function getUserPermissions(): array
    {
        return [
            'create user', 'edit user', 'delete user', 'force delete user', 'reset password',
        ];
    }
    public function setUp(): void
    {
        parent::setUp();

        $this->newUser = User::factory()->create();
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_user_is_created(): void
    {
        $this->post(route('users.store'), ['email' => 'sometestEmail@example.com'] + $this->newUser->toArray() + ['role' => 'admin']);

        Notification::assertSentTo($this->admins, UserCreated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_user_is_updated(): void
    {
        $this->withoutExceptionHandling();
        $this->put(
            route('users.update', ['user' => $this->newUser->id]),
            ['name' => 'test']
        );

        Notification::assertSentTo($this->admins, UserUpdated::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_user_is_deleted(): void
    {
        //delete the user
        $this->delete(
            route('users.destroy', ['user' => $this->newUser->id]),
            $this->newUser->toArray()
        );

        Notification::assertSentTo($this->admins, UserDeleted::class);
    }

    /** @test */
    public function notification_is_sent_to_the_admins_after_the_user_is_force_deleted(): void
    {
        Storage::fake();
        Storage::shouldReceive('directoryExists')
            ->andReturn(true);
        Storage::shouldReceive('deleteDirectory')
            ->andReturn(true);

        $this->delete(
            route('users.forceDestroy', ['user' => $this->newUser->id]),
            $this->newUser->toArray()
        );

        Notification::assertSentTo($this->admins, UserForceDeleted::class);
    }
    /** @test */
    public function notification_is_sent_to_the_admins_after_the_user_password_have_been_reset(): void
    {
        //delete the user
        $this->put(
            route('password.reset.update', ['user' => $this->newUser->id]),
        );

        Notification::assertSentTo($this->admins, UserResetPassword::class);
    }
}
