<?php

namespace Tests\Unit;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Models\PaymentDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belong_to_an_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $paymentDetail = PaymentDetail::factory()->create(['invoice_id' => $invoice->id]);

        $this->assertInstanceOf(Invoice::class, $paymentDetail->invoice);
        $this->assertEquals($invoice->id, $paymentDetail->invoice->id);
    }

    /** @test */
    public function it_belong_to_a_user(): void
    {
        $user = User::factory()->create();
        $paymentDetail = PaymentDetail::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $paymentDetail->user);
        $this->assertEquals($user->id, $paymentDetail->user->id);
    }

    /** @test */
    public function it_has_a_state(): void
    {
        $paymentDetail = PaymentDetail::factory()->create();

        $this->assertInstanceOf(InvoiceState::class, $paymentDetail->state);
    }

    /** @test */
    public function it_has_a_user_name_attribute(): void
    {
        $user = User::factory()->create();
        $paymentDetail = PaymentDetail::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->name, $paymentDetail->userName);
    }
}
