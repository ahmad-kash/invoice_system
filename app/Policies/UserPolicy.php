<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $admin): bool
    {
        return $admin->hasPermissionTo('show user');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $admin, User $user): bool
    {
        return $admin->hasPermissionTo('show user');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $admin): bool
    {
        return $admin->hasPermissionTo('create user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $admin, User $user): bool
    {
        return $admin->hasPermissionTo('edit user');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $admin, User $user): bool
    {
        return $admin->hasPermissionTo('delete user');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $admin, ?User $user = null): bool
    {
        return $admin->hasPermissionTo('restore user');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $admin, User $user): bool
    {
        return $admin->hasPermissionTo('force delete user');
    }
}
