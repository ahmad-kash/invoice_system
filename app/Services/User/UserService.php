<?php

namespace App\Services\User;

use App\DTO\UserDTO;
use App\Exceptions\Custom\UserNotFoundException;
use App\Models\User;
use App\Notifications\Database\User\UserCreated;
use App\Notifications\Database\User\UserDeleted;
use App\Notifications\Database\User\UserForceDeleted;
use App\Notifications\Database\User\UserResetPassword;
use App\Notifications\Database\User\UserUpdated;
use App\Services\Mail\MailService;
use App\Services\Notification\AdminNotifyService;
use Illuminate\Support\Str;

class UserService
{

    public function __construct(protected MailService $mailService, protected AdminNotifyService $adminNotifyService)
    {
    }
    public function getAllWithPagination()
    {
        return User::paginate(5);
    }

    private function getRandomPassword(): string
    {
        return Str::password(length: random_int(8, 20));
    }
    public function create(UserDTO $userDTO, string $role): User
    {
        $user = User::create(['password' => $this->getRandomPassword()] + $userDTO->toArray());
        $user->assignRole($role);

        $this->mailService->sendWelcomeMailTo($user);


        $this->adminNotifyService->notifyAdmins(new UserCreated($user, auth()->user()));
        return $user;
    }

    public function setNewPassword(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();
        if (!$user)
            throw new UserNotFoundException('user is not found');

        $user->update(['password' => $password] + ['email_verified_at' => now()]);

        return $user;
    }

    public function resetPassword(User $user): bool
    {
        $user->update(['password' => $this->getRandomPassword(), 'email_verified_at' => null]);

        $this->mailService->sendWelcomeMailTo($user);

        $this->adminNotifyService->notifyAdmins(new UserResetPassword($user, auth()->user()));
        return true;
    }

    public function update(User $user, UserDTO $userDTO, ?string $role): bool
    {
        $user->update($userDTO->toUpdateArray());
        if (!is_null($role))
            $user->syncRoles([$role]);

        $this->adminNotifyService->notifyAdmins(new UserUpdated($user, auth()->user()));

        return true;
    }

    public function delete(User $user): bool
    {
        $isDeleted = $user->delete();
        if ($isDeleted)
            $this->adminNotifyService->notifyAdmins(new UserDeleted($user, auth()->user()));

        return $isDeleted;
    }

    public function forceDelete(User $user): bool
    {
        $isDeleted = $user->forceDelete();
        if ($isDeleted)
            $this->adminNotifyService->notifyAdmins(new UserForceDeleted($user, auth()->user()));

        return $isDeleted;
    }
}
