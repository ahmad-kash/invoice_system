<?php

namespace Tests\Unit;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Services\Invoice\InvoicePaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Exceptions\InvalidArgumentException;
use Nette\ArgumentOutOfRangeException;
use tests\TestCase;

class InvoicePaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private Invoice $invoice;
    private InvoicePaymentService $invoicePaymentService;
    public function setUp(): void
    {
        parent::setUp();

        $this->invoice = Invoice::factory()->create(['total' => 1000]);
        $this->invoicePaymentService = new InvoicePaymentService();
    }

    /** @test */
    public function it_can_give_the_previous_payment_sum(): void
    {
        $this->signIn();
        $sum  = $this->invoicePaymentService->getSumOfAllPreviousPayments($this->invoice);

        $this->assertEquals($sum, 0);

        $this->invoicePaymentService->pay($this->invoice, 500);

        $sum  = $this->invoicePaymentService->getSumOfAllPreviousPayments($this->invoice);

        $this->assertEquals($sum, 500);
    }

    /** @test */
    public function it_can_pay_for_an_invoice(): void
    {
        $this->signIn();
        $this->invoicePaymentService->pay($this->invoice, 500);

        $this->assertDatabaseHas('payment_details', ['amount' => 500]);
    }

    /** @test */
    public function it_can_determine_invoice_state_depending_on_the_amount(): void
    {
        $this->invoicePaymentService->setAllPayments($this->invoice, 500);
        $this->assertEquals(InvoiceState::partiallyPaid, $this->invoicePaymentService->getNewState($this->invoice));

        $this->invoicePaymentService->setAllPayments($this->invoice, 1000);
        $this->assertEquals(InvoiceState::paid, $this->invoicePaymentService->getNewState($this->invoice));
    }

    /** @test */
    public function it_throw_an_exception_if_the_amount_plus_the_previous_paying_amounts_is_bigger_than_the_invoice_total(): void
    {
        $this->expectException(ArgumentOutOfRangeException::class);

        $this->invoicePaymentService->pay($this->invoice, 2000);
    }

    /** @test */
    public function it_throw_an_exception_if_the_amount_equal_to_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->invoicePaymentService->pay($this->invoice, 0);
    }
}
