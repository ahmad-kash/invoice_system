<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belong_to_an_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $attachment = InvoiceAttachment::factory()->create(['invoice_id' => $invoice->id]);

        $this->assertInstanceOf(Invoice::class, $attachment->invoice);
        $this->assertEquals($invoice->id, $attachment->invoice->id);
    }
}
