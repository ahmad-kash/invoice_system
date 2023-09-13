<?php

namespace App\Services\User;

use App\DTO\UserDTO;
use App\Exceptions\Custom\UserNotFoundException;
use App\Models\User;
use App\Services\Mail\MailService;
use Illuminate\Support\Str;

class UserService
{

    public function __construct(protected MailService $mailService)
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

        return true;
    }

    public function update(User $user, UserDTO $userDTO, ?string $role): bool
    {
        $user->update($userDTO->toUpdateArray());
        if (!is_null($role))
            $user->syncRoles([$role]);

        return true;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function forceDelete(User $user): bool
    {
        return $user->forceDelete();
    }
}
