<?php

namespace App\Notifications\Database\Role;

use App\Models\User;
use App\Notifications\Database\DatabaseNotification;
use Spatie\Permission\Models\Role;

abstract class RoleNotification extends DatabaseNotification
{
    public function __construct(private Role $role, private User $user)
    {
    }

    public function toDatabase(): array
    {
        return [
            'role_id' => $this->role->id,
            'role_name' => $this->role->name,
            'user_name' => $this->user->name,
        ] + $this->additionalData();
    }
}
