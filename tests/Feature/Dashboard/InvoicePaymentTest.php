<?php

namespace Tests\Feature\Dashboard;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Models\PaymentDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DashboardTestCase;

class InvoicePaymentTest extends DashboardTestCase
{

    public function getPermissions(): array
    {
        return [
            'create invoice',
            'edit invoice'
        ];
    }

    /** @test */
    public function user_can_see_see_the_payment_page(): void
    {
        $invoice = Invoice::factory()->create(['state' => InvoiceState::unPaid->value]);
        $this->get(route('invoices.payments.create', ['invoice' => $invoice->id]))
            ->assertOk()
            ->assertViewIs('invoice.payment.create')
            ->assertSee('دفع الفاتورة');
    }

    /** @test */
    public function user_can_make_a_payment(): void
    {
        $invoice = Invoice::factory()->create(['total' => 2000]);
        $this->withoutExceptionHandling();
        $data = [
            'amount' => 1000,
            'note' => 'test note'
        ];
        $this->post(route('invoices.payments.store', ['invoice' => $invoice->id]), $data)
            ->assertRedirect(route('invoices.show', ['invoice' => $invoice->id]));

        $this->assertDatabaseHas('payment_details', ['amount' => $data['amount'], 'note' => $data['note']]);
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForPayment
     */
    public function a_user_can_not_make_a_payment_with_invalid_data(string $fieldName, $amount = null, $note = null): void
    {
        $invoice = Invoice::factory()->create();
        $this->post(route('invoices.payments.store', ['invoice' => $invoice->id]), ['amount' => $amount, 'note' => $note])
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForPayment()
    {
        $faker = self::getFaker();

        return [
            'note in not a string' => ['note', 'amount' => $faker->randomNumber(2), 'note' => 5],

            'amount is null' => ['amount', 'amount' => null, 'note' => $faker->sentence()],
            'amount is not number' => ['amount', 'amount' => 'test', 'note' => null],
            'amount is equal to zero' => ['amount', 'amount' => 0],
            'amount is less than zero' => ['amount', 'amount' => -1],
        ];
    }
}
