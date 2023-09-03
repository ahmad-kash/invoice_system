<?php

namespace App\Services\Invoice;

use App\DTO\InvoiceDTO;
use Illuminate\Support\Str;

class InvoiceCalCulator
{


    public function __construct(private InvoiceDTO $invoice)
    {
    }

    public static function make(InvoiceDTO $invoice)
    {
        return new self($invoice);
    }
    public function execute()
    {
        $invoiceArray = $this->invoice->toArray();

        // calculate VAT_value and total
        $commissionAmountAfterDiscount = $this->invoice->commission_amount - $this->invoice->discount;
        $VATValue = $commissionAmountAfterDiscount * $this->invoice->VAT_rate / 100;
        $total = $VATValue + $commissionAmountAfterDiscount;

        $invoiceArray['VAT_value'] = $VATValue;
        $invoiceArray['total'] = $total;

        return InvoiceDTO::fromArray($invoiceArray);
    }
}
