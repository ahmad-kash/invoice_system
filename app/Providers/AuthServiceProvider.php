<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\InvoicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('make-a-payment', function (User $user) {
            return $user->hasPermissionTo('create invoice') && $user->hasPermissionTo('edit invoice');
        });

        Gate::define('reset-password', function (User $user) {
            return $user->hasPermissionTo('reset password');
        });
    }
}
