<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    // /** @test */
    // public function guest_can_see_register_page(): void
    // {
    //     $this->get('register')->assertOk();
    // }
    // /** @test */
    // public function authenticated_user_can_not_see_register_page(): void
    // {
    //     $this->getAuthUser()->get('register')->assertRedirect('home');
    // }

    // /** @test */
    // public function guest_can_register(): void
    // {
    //     $newUser = User::factory()->make(['email' => 'test@test.com', 'password' => bcrypt('123456789')]);

    //     $this->post('register', $newUser->toArray())->assertRedirect();
    // }
}
