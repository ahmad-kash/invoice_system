<?php

namespace Tests\Feature\Permission;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\PermissionRoleTestFactory;
use Tests\TestCase;
use Tests\UserTestBuilder;

class InvoicePaymentPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $spyUser;
    protected Invoice $invoice;

    public function setUp(): void
    {
        parent::setUp();

        (new PermissionRoleTestFactory)
            ->createPermissions(
                [
                    'create invoice',
                    'edit invoice',
                ]
            );

        $user = $this->partialMock(User::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasPermissionTo')->with('create invoice')->once()->andReturn('true');
            $mock->shouldReceive('hasPermissionTo')->with('edit invoice')->once()->andReturn('true');
        });
        $this->signIn($user);

        $this->invoice = Invoice::factory()->create();
    }

    /** @test */
    public function user_is_asked_if_he_has_create_invoice_and_edit_invoice_permissions_on_route_invoices_payments_create(): void
    {
        $this->get(route('invoices.payments.create', ['invoice' => $this->invoice->id]));
    }

    /** @test */
    public function user_is_asked_if_he_has_create_invoice_and_edit_invoice_permissions_on_route_invoices_payments_store(): void
    {
        $this->post(route('invoices.payments.store', ['invoice' => $this->invoice]), ['amount' => 10000, 'note' => 'test']);
    }
}
