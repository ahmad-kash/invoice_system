<?php

namespace App\Notifications\Database\User;

use App\Models\User;
use App\Notifications\Database\DatabaseNotification;

class UserNotification extends DatabaseNotification
{
    public function __construct(private User $user, private User $authUser)
    {
    }

    public function toDatabase(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'auth_user_name' => $this->authUser->name,

        ] + $this->additionalData();
    }
}
