<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function auth_user_can_logout(): void
    {
        $this->signIn()->post(route('logout'))->assertRedirectToRoute('login.create');
    }

    /** @test */
    public function logout_button_exist()
    {
        $this->signIn()->get(route('home'))->assertSeeTExt('تسجيل الخروج');
    }
}
