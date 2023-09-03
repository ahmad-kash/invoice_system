<?php

namespace Tests\Unit;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Models\PaymentDetail;
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
    public function it_has_a_state(): void
    {
        $paymentDetail = PaymentDetail::factory()->create();

        $this->assertInstanceOf(InvoiceState::class, $paymentDetail->state);
    }
}
