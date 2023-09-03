<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use Tests\Traits\HasCollectionAssert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use HasCollectionAssert;

    public function signIn(Authenticatable|MockInterface $user = null)
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
