<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_statistic(): void
    {
        $this->signIn();
        $this->get(route('home'))
            ->assertOk()
            ->assertViewHasAll([
                'totalInvoicesCount',
                'paidInvoicesCount',
                'unPaidInvoicesCount',
                'partiallyPaidInvoicesCount',
                'totalInvoicesSum',
                'paidInvoicesSum',
                'unPaidInvoicesSum',
                'partiallyPaidInvoicesSum'
            ]);
    }
}
