<?php

namespace Tests\Unit;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_state(): void
    {
        $invoice = Invoice::factory()->create();

        $this->assertInstanceOf(InvoiceState::class, $invoice->state);
    }

    /** @test */
    public function it_belong_to_a_product(): void
    {
        $product = Product::factory()->create();
        $invoice = Invoice::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $invoice->product);
        $this->assertEquals($product->id, $invoice->product->id);
    }

    /** @test */
    public function it_belong_to_a_section(): void
    {
        $section = Section::factory()->create();
        $invoice = Invoice::factory()->create(['section_id' => $section->id]);

        $this->assertInstanceOf(Section::class, $invoice->section);
        $this->assertEquals($section->id, $invoice->section->id);
    }

    /** @test */
    public function it_has_payment_details(): void
    {
        $invoice = Invoice::factory()->create();
        $paymentDetails = PaymentDetail::factory(2)->create(['invoice_id' => $invoice->id]);

        $this->assertInstanceOf(PaymentDetail::class, $invoice->paymentDetails->first());
        $this->assertEloquentCollectionsEqual($invoice->paymentDetails, $paymentDetails);
    }

    /** @test */
    public function it_has_attachment(): void
    {
        $invoice = Invoice::factory()->create();
        $attachment = InvoiceAttachment::factory(2)->create(['invoice_id' => $invoice->id]);

        $this->assertInstanceOf(InvoiceAttachment::class, $invoice->attachment->first());
        $this->assertEloquentCollectionsEqual($invoice->attachment, $attachment);
    }

    /** @test */
    public function it_use_soft_delete_trait(): void
    {
        $this->assertContains(SoftDeletes::class, class_uses(Invoice::class));
    }
}