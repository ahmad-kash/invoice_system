<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn(Authenticatable $user = null)
    {
        $authUser = $user ? $user : User::factory()->create();
        return $this->actingAs($authUser);
    }

    public function PartialMockWithRunningConstructor(string $abstract, array|string $trackedMethods)
    {
        if (is_string($trackedMethods))
            $trackedMethods = Arr::wrap($trackedMethods);

        $mock = Mockery::mock($abstract . '[' .  implode(',', $trackedMethods) . ']');
        $this->instance($abstract, $mock);
        return $mock;
    }
}
